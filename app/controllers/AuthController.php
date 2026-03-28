<?php
class AuthController extends Controller
{
    private User $userModel;
    private PasswordReset $resetModel;
    private LoginAttempt $attemptModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->resetModel = new PasswordReset();
        $this->attemptModel = new LoginAttempt();
    }

    public function loginForm(): void
    {
        if ($this->auth()) {
            $this->redirect('/painel');
            return;
        }
        $this->view('auth.login', ['error' => $_SESSION['login_error'] ?? null]);
        unset($_SESSION['login_error']);
    }

    public function login(): void
    {
        $this->validateCsrf();
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $ip = $this->clientIp();

        if (!$email || !$password) {
            $_SESSION['login_error'] = 'Preencha e-mail e senha.';
            $this->redirect('/login');
            return;
        }

        // Rate limiting
        if ($this->attemptModel->isBlocked($email, $ip, LOGIN_MAX_ATTEMPTS, LOGIN_LOCKOUT_MINUTES)) {
            $_SESSION['login_error'] = 'Muitas tentativas. Tente novamente em ' . LOGIN_LOCKOUT_MINUTES . ' minutos.';
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->attemptModel->record($email, $ip);
            $_SESSION['login_error'] = 'E-mail ou senha incorretos.';
            $this->redirect('/login');
            return;
        }

        // Login bem-sucedido - limpar tentativas
        $this->attemptModel->clearForEmail($email);
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;
        $this->redirect('/painel');
    }

    public function logout(): void
    {
        // Aceita tanto GET quanto POST para compatibilidade, mas POST é preferido
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirect('/');
    }

    public function registerForm(): void
    {
        if ($this->auth()) {
            $this->redirect('/painel');
            return;
        }
        $this->view('auth.register', [
            'error' => $_SESSION['register_error'] ?? null,
            'old' => $_SESSION['register_old'] ?? []
        ]);
        unset($_SESSION['register_error'], $_SESSION['register_old']);
    }

    public function register(): void
    {
        $this->validateCsrf();
        $name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if (!$name || !$email || !$password) {
            $_SESSION['register_error'] = 'Preencha todos os campos.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }
        if (!isset($_POST['terms'])) {
            $_SESSION['register_error'] = 'Você precisa aceitar os Termos de Uso para se cadastrar.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }
        if (strlen($password) < 6) {
            $_SESSION['register_error'] = 'Senha deve ter no mínimo 6 caracteres.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }
        if ($password !== $confirm) {
            $_SESSION['register_error'] = 'As senhas não coincidem.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['register_error'] = 'Este e-mail já está cadastrado.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }

        $photo = UploadHelper::handleImage('photo', UPLOAD_USERS, 'user');
        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'photo' => $photo
        ]);

        // Notificação de boas-vindas
        $notif = new Notification();
        $notif->create($userId, 'welcome', 'Bem-vindo ao Green Air! 🌳', 'Comece cadastrando sua primeira árvore e ganhe pontos.', '/cadastrar-arvore');

        $_SESSION['register_success'] = true;
        $this->redirect('/login');
    }

    public function forgotForm(): void
    {
        $this->view('auth.forgot', ['message' => $_SESSION['forgot_message'] ?? null]);
        unset($_SESSION['forgot_message']);
    }

    public function forgot(): void
    {
        $this->validateCsrf();
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        if (!$email) {
            $_SESSION['forgot_message'] = ['type' => 'error', 'text' => 'Informe o e-mail.'];
            $this->redirect('/esqueci-senha');
            return;
        }
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->resetModel->create($email, $token);
            $link = BASE_URL . 'redefinir-senha?token=' . $token;
            $msg = "Olá,\n\nAcesse o link para redefinir sua senha:\n$link\n\nVálido por 24 horas.";
            @mail($email, 'Green Air - Redefinir senha', $msg);
        }
        $_SESSION['forgot_message'] = ['type' => 'success', 'text' => 'Se o e-mail existir, você receberá o link para redefinir a senha.'];
        $this->redirect('/esqueci-senha');
    }

    public function resetForm(): void
    {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            $_SESSION['login_error'] = 'Link inválido.';
            $this->redirect('/login');
            return;
        }
        $row = $this->resetModel->findValidToken($token);
        if (!$row) {
            $_SESSION['login_error'] = 'Link expirado ou inválido.';
            $this->redirect('/login');
            return;
        }
        $this->view('auth.reset', ['token' => $token]);
    }

    public function resetPassword(): void
    {
        $this->validateCsrf();
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        if (!$token || !$password || $password !== $confirm || strlen($password) < 6) {
            $_SESSION['login_error'] = 'Dados inválidos ou senhas não coincidem.';
            $this->redirect('/login');
            return;
        }
        $row = $this->resetModel->findValidToken($token);
        if (!$row) {
            $_SESSION['login_error'] = 'Link expirado.';
            $this->redirect('/login');
            return;
        }
        $this->userModel->updatePasswordByEmail($row['email'], password_hash($password, PASSWORD_DEFAULT));
        $this->resetModel->deleteByToken($token);
        $_SESSION['forgot_message'] = ['type' => 'success', 'text' => 'Senha alterada com sucesso. Faça login.'];
        $this->redirect('/login');
    }
}
