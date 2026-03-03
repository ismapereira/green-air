<?php
class UserController extends Controller
{
    public function profile(): void
    {
        $user = $this->requireAuth();
        $logModel = new ContributionLog();
        $contributions = $logModel->byUser($user['id'], 20);
        $this->view('user.profile', [
            'user' => $user,
            'currentUser' => $user,
            'contributions' => $contributions
        ]);
    }

    public function updateProfile(): void
    {
        $user = $this->requireAuth();
        $userModel = new User();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        if (!$name) {
            $_SESSION['error'] = 'Nome é obrigatório.';
            $this->redirect('/perfil');
            return;
        }
        $data = ['name' => $name];
        $photo = $this->handlePhotoUpload();
        if ($photo) $data['photo'] = $photo;
        $password = $_POST['password'] ?? '';
        if ($password !== '') {
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Senha deve ter no mínimo 6 caracteres.';
                $this->redirect('/perfil');
                return;
            }
            $data['password'] = $password;
        }
        $userModel->update($user['id'], $data);
        $_SESSION['user'] = $userModel->findById($user['id']);
        unset($_SESSION['user']['password']);
        $_SESSION['success'] = 'Perfil atualizado.';
        $this->redirect('/perfil');
    }

    private function handlePhotoUpload(): ?string
    {
        if (empty($_FILES['photo']['tmp_name']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['photo']['tmp_name']);
        if (!in_array($mime, ALLOWED_IMAGE_TYPES) || $_FILES['photo']['size'] > MAX_FILE_SIZE) {
            return null;
        }
        if (!is_dir(UPLOAD_USERS)) mkdir(UPLOAD_USERS, 0755, true);
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $name = 'user_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_USERS . '/' . $name)) {
            return $name;
        }
        return null;
    }
}
