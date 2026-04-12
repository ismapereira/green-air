<?php
/**
 * Controller admin para sugestões colaborativas da comunidade.
 */
class AdminCommunitySuggestionController extends Controller
{
    private const CATEGORIES = [
        'feature'     => 'Nova funcionalidade',
        'species'     => 'Nova espécie',
        'improvement' => 'Melhoria',
        'bug'         => 'Problema',
        'other'       => 'Outro',
    ];

    private const STATUSES = [
        'pending'     => 'Pendente',
        'reviewed'    => 'Em análise',
        'accepted'    => 'Aceita',
        'implemented' => 'Implementada',
        'rejected'    => 'Rejeitada',
    ];

    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new CommunitySuggestion();
        $status = $_GET['status'] ?? 'pending';
        $category = $_GET['category'] ?? 'all';

        $this->view('admin.community-suggestions.index', [
            'user' => $user,
            'suggestions' => $model->all($status, $category, 200),
            'currentStatus' => $status,
            'currentCategory' => $category,
            'categories' => self::CATEGORIES,
            'statuses' => self::STATUSES,
            'stats' => $model->countByCategory(),
            'pendingCount' => $model->pendingCount()
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->requireAdmin();
        $model = new CommunitySuggestion();
        $suggestion = $model->findById((int)$id);
        if (!$suggestion) {
            $_SESSION['admin_error'] = 'Sugestão não encontrada.';
            $this->redirect('/admin/comunidade');
            return;
        }
        $this->view('admin.community-suggestions.show', [
            'user' => $user,
            'suggestion' => $suggestion,
            'categories' => self::CATEGORIES,
            'statuses' => self::STATUSES
        ]);
    }

    public function respond(string $id): void
    {
        $this->validateCsrf();
        $admin = $this->requireAdmin();
        $model = new CommunitySuggestion();

        $suggestion = $model->findById((int)$id);
        if (!$suggestion) {
            $this->redirect('/admin/comunidade');
            return;
        }

        $status = $_POST['status'] ?? 'reviewed';
        $response = trim($_POST['admin_response'] ?? '');

        if (!array_key_exists($status, self::STATUSES)) {
            $status = 'reviewed';
        }

        $model->respond((int)$id, $admin['id'], $status, $response ?: null);

        // Notificar o autor
        $statusLabel = self::STATUSES[$status] ?? $status;
        $notif = new Notification();
        $notif->create(
            $suggestion['user_id'],
            'suggestion_update',
            'Sugestão atualizada',
            'Sua sugestão "' . mb_substr($suggestion['title'], 0, 50) . '" foi atualizada para: ' . $statusLabel . '.',
            '/sugestoes'
        );

        // Verificar conquistas do autor (ex: sugestão aceita)
        BadgeChecker::check($suggestion['user_id']);

        $_SESSION['admin_success'] = "Sugestão #{$id} atualizada para: {$statusLabel}.";
        $this->redirect('/admin/comunidade');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $this->requireAdmin();
        $model = new CommunitySuggestion();
        $model->delete((int)$id);
        $_SESSION['admin_success'] = 'Sugestão excluída.';
        $this->redirect('/admin/comunidade');
    }
}
