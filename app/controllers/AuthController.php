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

        // Verificar CAPTCHA (ignorado se não configurado)
        if (!CaptchaHelper::verify()) {
            $_SESSION['login_error'] = 'Verificação anti-robô falhou. Tente novamente.';
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

        // Verificar CAPTCHA (ignorado se não configurado)
        if (!CaptchaHelper::verify()) {
            $_SESSION['register_error'] = 'Verificação anti-robô falhou. Tente novamente.';
            $_SESSION['register_old'] = $_POST;
            $this->redirect('/registro');
            return;
        }

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
        $notif->create($userId, 'welcome', 'Bem-vindo ao Green Air!', 'Comece cadastrando sua primeira árvore e ganhe pontos.', '/cadastrar-arvore');

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

        // Verificar CAPTCHA (ignorado se não configurado)
        if (!CaptchaHelper::verify()) {
            $_SESSION['forgot_message'] = ['type' => 'error', 'text' => 'Verificação anti-robô falhou. Tente novamente.'];
            $this->redirect('/esqueci-senha');
            return;
        }

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

            $emailSent = $this->sendResetEmail($email, $user['name'] ?? '', $link);

            if (!$emailSent) {
                $_SESSION['forgot_message'] = [
                    'type' => 'error',
                    'text' => 'Não foi possível enviar o e-mail. Verifique se as configurações de SMTP estão corretas no arquivo .env.'
                ];
                $this->redirect('/esqueci-senha');
                return;
            }
        }

        // Mensagem genérica por segurança (não revela se o e-mail existe)
        $_SESSION['forgot_message'] = ['type' => 'success', 'text' => 'Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha em instantes.'];
        $this->redirect('/esqueci-senha');
    }

    /**
     * Send password reset email via SMTP
     */
    private function sendResetEmail(string $to, string $name, string $link): bool
    {
        $mailer = new SmtpMailer();
        $subject = 'Green Air - Redefinir senha';
        $firstName = explode(' ', $name)[0] ?: 'Usuário';

        // HTML email body
        $htmlBody = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:Arial,sans-serif;background:#f0fdf4;margin:0;padding:20px">';
        $htmlBody .= '<div style="max-width:500px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.1)">';
        $htmlBody .= '<div style="background:linear-gradient(135deg,#059669,#047857);padding:2rem;text-align:center;color:#fff">';
        $htmlBody .= '<h1 style="margin:0;font-size:1.5rem">Green Air</h1>';
        $htmlBody .= '<p style="margin:0.5rem 0 0;opacity:0.9">Redefinição de Senha</p>';
        $htmlBody .= '</div>';
        $htmlBody .= '<div style="padding:2rem">';
        $htmlBody .= '<p>Olá, <strong>' . htmlspecialchars($firstName) . '</strong>!</p>';
        $htmlBody .= '<p>Recebemos uma solicitação para redefinir a senha da sua conta no Green Air.</p>';
        $htmlBody .= '<p style="text-align:center;margin:1.5rem 0">';
        $htmlBody .= '<a href="' . htmlspecialchars($link) . '" style="display:inline-block;background:#059669;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:bold">Redefinir Minha Senha</a>';
        $htmlBody .= '</p>';
        $htmlBody .= '<p style="color:#64748b;font-size:0.9rem">Este link é válido por <strong>24 horas</strong>. Se você não solicitou a redefinição, ignore este e-mail com segurança.</p>';
        $htmlBody .= '<hr style="border:none;border-top:1px solid #e5e7eb;margin:1.5rem 0">';
        $htmlBody .= '<p style="color:#94a3b8;font-size:0.8rem">Se o botão não funcionar, copie e cole este link no navegador:<br><a href="' . htmlspecialchars($link) . '" style="color:#059669;word-break:break-all">' . htmlspecialchars($link) . '</a></p>';
        $htmlBody .= '</div></div></body></html>';

        // Plain text fallback
        $textBody = "Olá, {$firstName}!\n\n";
        $textBody .= "Recebemos uma solicitação para redefinir a senha da sua conta no Green Air.\n\n";
        $textBody .= "Acesse o link abaixo para redefinir sua senha:\n{$link}\n\n";
        $textBody .= "Este link é válido por 24 horas.\n";
        $textBody .= "Se você não solicitou a redefinição, ignore este e-mail.\n";

        return $mailer->send($to, $subject, $htmlBody, $textBody);
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
