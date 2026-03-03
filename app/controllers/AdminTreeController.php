<?php
class AdminTreeController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $treeModel = new Tree();
        $filters = [
            'species_id' => !empty($_GET['species_id']) ? (int)$_GET['species_id'] : null,
            'status_id' => !empty($_GET['status_id']) ? (int)$_GET['status_id'] : null
        ];
        $trees = $treeModel->all($filters);
        $speciesModel = new TreeSpecies();
        $statusModel = new TreeStatus();
        $this->view('admin.trees.index', [
            'user' => $user,
            'trees' => $trees,
            'species' => $speciesModel->all(),
            'statuses' => $statusModel->all()
        ]);
    }

    public function edit(string $id): void
    {
        $user = $this->requireAdmin();
        $treeModel = new Tree();
        $tree = $treeModel->findById((int)$id);
        if (!$tree) {
            $this->redirect('/admin/arvores');
            return;
        }
        $speciesModel = new TreeSpecies();
        $statusModel = new TreeStatus();
        $this->view('admin.trees.edit', [
            'user' => $user,
            'tree' => $tree,
            'species' => $speciesModel->all(),
            'statuses' => $statusModel->all()
        ]);
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $treeModel = new Tree();
        $tree = $treeModel->findById($id);
        if (!$tree) {
            $this->redirect('/admin/arvores');
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
            $photo = $this->handleUpload();
            if ($photo) $data['photo'] = $photo;
        }
        $treeModel->update($id, $data);
        $_SESSION['admin_success'] = 'Árvore atualizada.';
        $this->redirect('/admin/arvores');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $treeModel = new Tree();
        $treeModel->delete($id);
        $_SESSION['admin_success'] = 'Árvore removida.';
        $this->redirect('/admin/arvores');
    }

    private function handleUpload(): ?string
    {
        if (empty($_FILES['photo']['tmp_name']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) return null;
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!in_array($finfo->file($_FILES['photo']['tmp_name']), ALLOWED_IMAGE_TYPES) || $_FILES['photo']['size'] > MAX_FILE_SIZE) return null;
        if (!is_dir(UPLOAD_TREES)) mkdir(UPLOAD_TREES, 0755, true);
        $name = 'tree_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . (pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION) ?: 'jpg');
        return move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_TREES . '/' . $name) ? $name : null;
    }
}
