<?php
class AdminTreeStatusController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new TreeStatus();
        $this->view('admin.status.index', ['user' => $user, 'statuses' => $model->all()]);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $this->requireAdmin();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $desc = trim(filter_var($_POST['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        if (!$name) {
            $_SESSION['admin_error'] = 'Nome é obrigatório.';
            $this->redirect('/admin/status');
            return;
        }
        $model = new TreeStatus();
        $model->create(['name' => $name, 'description' => $desc]);
        $_SESSION['admin_success'] = 'Status cadastrado.';
        $this->redirect('/admin/status');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $this->requireAdmin();
        $id = (int)$id;
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $desc = trim(filter_var($_POST['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        if (!$name) {
            $_SESSION['admin_error'] = 'Nome é obrigatório.';
            $this->redirect('/admin/status');
            return;
        }
        $model = new TreeStatus();
        $model->update($id, ['name' => $name, 'description' => $desc]);
        $_SESSION['admin_success'] = 'Status atualizado.';
        $this->redirect('/admin/status');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $this->requireAdmin();
        $id = (int)$id;
        // Verificar dependências
        $treeModel = new Tree();
        $count = $treeModel->countByStatus($id);
        if ($count > 0) {
            $_SESSION['admin_error'] = "Não é possível excluir: {$count} árvore(s) vinculada(s) a este status.";
            $this->redirect('/admin/status');
            return;
        }
        $model = new TreeStatus();
        $model->delete($id);
        $_SESSION['admin_success'] = 'Status removido.';
        $this->redirect('/admin/status');
    }
}
