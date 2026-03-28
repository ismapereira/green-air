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
        $this->validateCsrf();
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
            $photo = UploadHelper::handleImage('photo', UPLOAD_TREES, 'tree');
            if ($photo) {
                UploadHelper::deleteOld($tree['photo'], UPLOAD_TREES);
                $data['photo'] = $photo;
            }
        }
        $treeModel->update($id, $data);
        $_SESSION['admin_success'] = 'Árvore atualizada.';
        $this->redirect('/admin/arvores');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $this->requireAdmin();
        $id = (int)$id;
        $treeModel = new Tree();
        $tree = $treeModel->findById($id);
        if ($tree) {
            UploadHelper::deleteOld($tree['photo'] ?? null, UPLOAD_TREES);
        }
        $treeModel->delete($id);
        $_SESSION['admin_success'] = 'Árvore removida.';
        $this->redirect('/admin/arvores');
    }
}
