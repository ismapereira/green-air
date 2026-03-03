<?php
/**
 * Controller base - helpers de view e redirect
 */
class Controller
{
    protected function view(string $view, array $data = []): void
    {
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

    protected function auth(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
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
        if ((int)($user['level_id'] ?? 0) !== LEVEL_OURO) {
            $this->redirect('/painel');
        }
        return $user;
    }
}
