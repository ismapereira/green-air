<?php
/**
 * Controller base - Green Air v2.0
 * CSRF, autenticação, helpers de view/redirect/json
 */
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        // Sempre disponibilizar CSRF token e notificações não lidas para views
        $data['csrfToken'] = $this->csrfToken();
        $user = $this->auth();
        if ($user) {
            $notifModel = new Notification();
            $data['unreadNotifications'] = $notifModel->unreadCount($user['id']);
        } else {
            $data['unreadNotifications'] = 0;
        }
        extract($data);
        $viewPath = ROOT_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo 'View não encontrada: ' . htmlspecialchars($view);
            return;
        }
        require $viewPath;
    }

    protected function redirect(string $url, int $code = 302): void
    {
        $url = strpos($url, 'http') === 0 ? $url : (BASE_PATH . '/' . ltrim($url, '/'));
        header('Location: ' . $url, true, $code);
        exit;
    }

    protected function json($data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ---- Autenticação ----

    protected function auth(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireAuth(): array
    {
        $user = $this->auth();
        if (!$user) {
            $this->redirect('/login');
        }
        return $user;
    }

    protected function requireAdmin(): array
    {
        $user = $this->requireAuth();
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/painel');
        }
        return $user;
    }

    // ---- CSRF ----

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo 'Token CSRF inválido. Recarregue a página e tente novamente.';
            exit;
        }
    }

    // ---- Helpers ----

    protected function clientIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
