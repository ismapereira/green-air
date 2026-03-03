<?php
class AdminSpeciesController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new TreeSpecies();
        $this->view('admin.species.index', ['user' => $user, 'species' => $model->all()]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $scientific = trim(filter_var($_POST['scientific_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        if (!$name) {
            $_SESSION['admin_error'] = 'Nome é obrigatório.';
            $this->redirect('/admin/especies');
            return;
        }
        $model = new TreeSpecies();
        $model->create(['name' => $name, 'scientific_name' => $scientific]);
        $_SESSION['admin_success'] = 'Espécie cadastrada.';
        $this->redirect('/admin/especies');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $scientific = trim(filter_var($_POST['scientific_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS)) ?: null;
        if (!$name) {
            $_SESSION['admin_error'] = 'Nome é obrigatório.';
            $this->redirect('/admin/especies');
            return;
        }
        $model = new TreeSpecies();
        $model->update($id, ['name' => $name, 'scientific_name' => $scientific]);
        $_SESSION['admin_success'] = 'Espécie atualizada.';
        $this->redirect('/admin/especies');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $model = new TreeSpecies();
        $model->delete($id);
        $_SESSION['admin_success'] = 'Espécie removida.';
        $this->redirect('/admin/especies');
    }
}
