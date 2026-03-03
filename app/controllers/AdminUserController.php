<?php
class AdminUserController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $userModel = new User();
        $this->view('admin.users.index', ['user' => $user, 'users' => $userModel->all()]);
    }

    public function create(): void
    {
        $user = $this->requireAdmin();
        $levelModel = new \stdClass();
        $db = Database::getConnection();
        $stmt = $db->query('SELECT * FROM user_levels ORDER BY min_points');
        $levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin.users.form', ['user' => $user, 'levels' => $levels, 'edit' => null]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $userModel = new User();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $levelId = (int)($_POST['level_id'] ?? 1);
        if (!$name || !$email) {
            $_SESSION['admin_error'] = 'Nome e e-mail são obrigatórios.';
            $this->redirect('/admin/usuarios/novo');
            return;
        }
        if ($userModel->findByEmail($email)) {
            $_SESSION['admin_error'] = 'E-mail já cadastrado.';
            $this->redirect('/admin/usuarios/novo');
            return;
        }
        if (strlen($password) < 6) $password = bin2hex(random_bytes(4));
        $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'level_id' => $levelId
        ]);
        $_SESSION['admin_success'] = 'Usuário criado.';
        $this->redirect('/admin/usuarios');
    }

    public function edit(string $id): void
    {
        $user = $this->requireAdmin();
        $userModel = new User();
        $edit = $userModel->findById((int)$id);
        if (!$edit) {
            $this->redirect('/admin/usuarios');
            return;
        }
        $db = Database::getConnection();
        $levels = $db->query('SELECT * FROM user_levels ORDER BY min_points')->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin.users.form', ['user' => $user, 'levels' => $levels, 'edit' => $edit]);
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $userModel = new User();
        $edit = $userModel->findById($id);
        if (!$edit) {
            $this->redirect('/admin/usuarios');
            return;
        }
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $levelId = (int)($_POST['level_id'] ?? 1);
        $points = (int)($_POST['points'] ?? $edit['points']);
        if (!$name || !$email) {
            $_SESSION['admin_error'] = 'Nome e e-mail são obrigatórios.';
            $this->redirect('/admin/usuarios/editar/' . $id);
            return;
        }
        $data = ['name' => $name, 'email' => $email, 'level_id' => $levelId, 'points' => $points];
        if (!empty($_POST['password'])) $data['password'] = $_POST['password'];
        $userModel->update($id, $data);
        $_SESSION['admin_success'] = 'Usuário atualizado.';
        $this->redirect('/admin/usuarios');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $userModel = new User();
        $edit = $userModel->findById($id);
        if (!$edit) {
            $this->redirect('/admin/usuarios');
            return;
        }
        $db = Database::getConnection();
        $db->prepare('UPDATE trees SET user_id = 1 WHERE user_id = ?')->execute([$id]);
        $db->prepare('DELETE FROM contributions_log WHERE user_id = ?')->execute([$id]);
        $db->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        $_SESSION['admin_success'] = 'Usuário removido.';
        $this->redirect('/admin/usuarios');
    }
}
