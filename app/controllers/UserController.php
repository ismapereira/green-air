<?php
class UserController extends Controller
{
    public function profile(): void
    {
        $user = $this->requireAuth();
        $userModel = new User();
        $logModel = new ContributionLog();
        $contributions = $logModel->byUser($user['id'], 20);
        $levelProgress = $userModel->levelProgress($user);

        $this->view('user.profile', [
            'user' => $user,
            'currentUser' => $user,
            'contributions' => $contributions,
            'levelProgress' => $levelProgress
        ]);
    }

    public function updateProfile(): void
    {
        $this->validateCsrf();
        $user = $this->requireAuth();
        $userModel = new User();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$name) {
            $_SESSION['error'] = 'Nome é obrigatório.';
            $this->redirect('/perfil');
            return;
        }

        $data = ['name' => $name];

        $photo = UploadHelper::handleImage('photo', UPLOAD_USERS, 'user');
        if ($photo) {
            UploadHelper::deleteOld($user['photo'] ?? null, UPLOAD_USERS);
            $data['photo'] = $photo;
        }

        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        if ($password !== '') {
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Senha deve ter no mínimo 6 caracteres.';
                $this->redirect('/perfil');
                return;
            }
            if ($password !== $confirm) {
                $_SESSION['error'] = 'As senhas não coincidem.';
                $this->redirect('/perfil');
                return;
            }
            $data['password'] = $password;
        }

        $userModel->update($user['id'], $data);
        $_SESSION['user'] = $userModel->findById($user['id']);
        unset($_SESSION['user']['password']);
        $_SESSION['success'] = 'Perfil atualizado com sucesso!';
        $this->redirect('/perfil');
    }
}
