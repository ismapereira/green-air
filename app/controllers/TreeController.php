<?php
class TreeController extends Controller
{
    private Tree $treeModel;
    private TreeSpecies $speciesModel;
    private TreeStatus $statusModel;
    private User $userModel;
    private ContributionLog $logModel;

    public function __construct()
    {
        $this->treeModel = new Tree();
        $this->speciesModel = new TreeSpecies();
        $this->statusModel = new TreeStatus();
        $this->userModel = new User();
        $this->logModel = new ContributionLog();
    }

    public function myTrees(): void
    {
        $user = $this->requireAuth();
        $trees = $this->treeModel->byUser($user['id']);
        $this->view('tree.my-trees', ['user' => $user, 'currentUser' => $user, 'trees' => $trees]);
    }

    public function create(): void
    {
        $user = $this->requireAuth();
        $this->view('tree.create', [
            'user' => $user,
            'currentUser' => $user,
            'species' => $this->speciesModel->all(),
            'statuses' => $this->statusModel->all(),
            'error' => $_SESSION['tree_error'] ?? null,
            'old' => $_SESSION['tree_old'] ?? []
        ]);
        unset($_SESSION['tree_error'], $_SESSION['tree_old']);
    }

    public function store(): void
    {
        $user = $this->requireAuth();
        $lat = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $lng = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;
        if ($lat === null || $lng === null) {
            $_SESSION['tree_error'] = 'Ative a localização no navegador e tente novamente.';
            $_SESSION['tree_old'] = $_POST;
            $this->redirect('/cadastrar-arvore');
            return;
        }
        $speciesId = (int)($_POST['species_id'] ?? 0);
        $statusId = (int)($_POST['status_id'] ?? 0);
        if (!$speciesId || !$statusId) {
            $_SESSION['tree_error'] = 'Selecione espécie e status.';
            $_SESSION['tree_old'] = $_POST;
            $this->redirect('/cadastrar-arvore');
            return;
        }
        $photo = $this->handleTreePhotoUpload();
        if (!$photo) {
            $_SESSION['tree_error'] = 'Envie uma foto da árvore (JPEG, PNG ou WebP, até 5MB).';
            $_SESSION['tree_old'] = $_POST;
            $this->redirect('/cadastrar-arvore');
            return;
        }
        $address = trim(filter_var($_POST['address'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        $size = in_array($_POST['size'] ?? '', ['Pequeno', 'Médio', 'Grande']) ? $_POST['size'] : null;
        $age = !empty($_POST['age_approx']) ? (int)$_POST['age_approx'] : null;
        $observations = trim(filter_var($_POST['observations'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        $id = $this->treeModel->create([
            'user_id' => $user['id'],
            'species_id' => $speciesId,
            'status_id' => $statusId,
            'latitude' => $lat,
            'longitude' => $lng,
            'address' => $address,
            'size' => $size,
            'age_approx' => $age,
            'observations' => $observations,
            'photo' => $photo
        ]);
        $this->userModel->addPoints($user['id'], POINTS_NEW_TREE);
        $this->logModel->log($user['id'], $id, ContributionLog::ACTION_ADD_TREE, POINTS_NEW_TREE);
        $_SESSION['success'] = 'Árvore cadastrada com sucesso! +' . POINTS_NEW_TREE . ' pontos.';
        $this->redirect('/minhas-arvores');
    }

    public function edit(string $id): void
    {
        $user = $this->requireAuth();
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $canEdit = ($tree['user_id'] == $user['id']) || ((int)$user['level_id'] === LEVEL_OURO);
        if (!$canEdit) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $this->view('tree.edit', [
            'user' => $user,
            'currentUser' => $user,
            'tree' => $tree,
            'species' => $this->speciesModel->all(),
            'statuses' => $this->statusModel->all(),
            'error' => $_SESSION['tree_error'] ?? null,
            'old' => $_SESSION['tree_old'] ?? []
        ]);
        unset($_SESSION['tree_error'], $_SESSION['tree_old']);
    }

    public function update(string $id): void
    {
        $user = $this->requireAuth();
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $canEdit = ($tree['user_id'] == $user['id']) || ((int)$user['level_id'] === LEVEL_OURO);
        if (!$canEdit) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $data = [
            'species_id' => (int)($_POST['species_id'] ?? 0),
            'status_id' => (int)($_POST['status_id'] ?? 0),
            'latitude' => (float)($_POST['latitude'] ?? 0),
            'longitude' => (float)($_POST['longitude'] ?? 0),
            'address' => trim(filter_var($_POST['address'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null,
            'size' => in_array($_POST['size'] ?? '', ['Pequeno', 'Médio', 'Grande']) ? $_POST['size'] : null,
            'age_approx' => !empty($_POST['age_approx']) ? (int)$_POST['age_approx'] : null,
            'observations' => trim(filter_var($_POST['observations'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null
        ];
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = $this->handleTreePhotoUpload();
            if ($photo) $data['photo'] = $photo;
        }
        $this->treeModel->update($id, $data);
        if ((int)$user['level_id'] === LEVEL_OURO) {
            $this->logModel->log($user['id'], $id, ContributionLog::ACTION_EDIT_TREE, 0);
        }
        $_SESSION['success'] = 'Árvore atualizada.';
        $this->redirect('/minhas-arvores');
    }

    public function suggest(string $id): void
    {
        $user = $this->requireAuth();
        if ((int)$user['level_id'] < LEVEL_PRATA) {
            $_SESSION['error'] = 'Nível Prata necessário para sugerir atualizações.';
            $this->redirect('/minhas-arvores');
            return;
        }
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $suggestion = trim(filter_var($_POST['suggestion'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        if (!$suggestion) {
            $_SESSION['error'] = 'Descreva a sugestão.';
            $this->redirect('/arvore/editar/' . $id);
            return;
        }
        $this->logModel->log($user['id'], $id, ContributionLog::ACTION_SUGGEST_UPDATE, 0);
        $_SESSION['success'] = 'Sugestão registrada. Um moderador pode aprovar (+3 pontos).';
        $this->redirect('/minhas-arvores');
    }

    private function handleTreePhotoUpload(): ?string
    {
        if (empty($_FILES['photo']['tmp_name']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['photo']['tmp_name']);
        if (!in_array($mime, ALLOWED_IMAGE_TYPES) || $_FILES['photo']['size'] > MAX_FILE_SIZE) {
            return null;
        }
        if (!is_dir(UPLOAD_TREES)) {
            mkdir(UPLOAD_TREES, 0755, true);
        }
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $name = 'tree_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_TREES . '/' . $name)) {
            return $name;
        }
        return null;
    }
}
