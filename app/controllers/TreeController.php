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

    public function show(string $id): void
    {
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            http_response_code(404);
            require ROOT_PATH . '/app/views/errors/404.php';
            exit;
        }
        $this->view('tree.show', [
            'tree' => $tree,
            'currentUser' => $this->auth()
        ]);
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
        $this->validateCsrf();
        $user = $this->requireAuth();
        $lat = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $lng = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;

        if ($lat === null || $lng === null || ($lat == 0 && $lng == 0)) {
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

        $photo = UploadHelper::handleImage('photo', UPLOAD_TREES, 'tree');
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

        // Atualizar sessão com dados frescos
        $freshUser = $this->userModel->findById($user['id']);
        unset($freshUser['password']);
        $_SESSION['user'] = $freshUser;

        $_SESSION['success'] = 'Árvore cadastrada com sucesso! +' . POINTS_NEW_TREE . ' pontos 🌳';
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
        $canEdit = ($tree['user_id'] == $user['id']) || (($user['role'] ?? '') === 'admin');
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
        $this->validateCsrf();
        $user = $this->requireAuth();
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $canEdit = ($tree['user_id'] == $user['id']) || (($user['role'] ?? '') === 'admin');
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
            $photo = UploadHelper::handleImage('photo', UPLOAD_TREES, 'tree');
            if ($photo) {
                UploadHelper::deleteOld($tree['photo'], UPLOAD_TREES);
                $data['photo'] = $photo;
            }
        }

        $this->treeModel->update($id, $data);
        if (($user['role'] ?? '') === 'admin') {
            $this->logModel->log($user['id'], $id, ContributionLog::ACTION_EDIT_TREE, 0);
        }
        $_SESSION['success'] = 'Árvore atualizada com sucesso.';
        $this->redirect('/minhas-arvores');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $user = $this->requireAuth();
        $id = (int)$id;
        $tree = $this->treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/minhas-arvores');
            return;
        }
        $canDelete = ($tree['user_id'] == $user['id']) || (($user['role'] ?? '') === 'admin');
        if (!$canDelete) {
            $this->redirect('/minhas-arvores');
            return;
        }

        UploadHelper::deleteOld($tree['photo'], UPLOAD_TREES);
        $this->treeModel->delete($id);
        $_SESSION['success'] = 'Árvore removida.';
        $this->redirect('/minhas-arvores');
    }

    public function suggest(string $id): void
    {
        $this->validateCsrf();
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

        // Persistir sugestão na tabela
        $suggModel = new TreeSuggestion();
        $suggModel->create($user['id'], $id, $suggestion);
        $this->logModel->log($user['id'], $id, ContributionLog::ACTION_SUGGEST_UPDATE, 0);

        $_SESSION['success'] = 'Sugestão registrada! Um moderador irá avaliar.';
        $this->redirect('/minhas-arvores');
    }
}
