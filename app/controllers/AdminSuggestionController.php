<?php
class AdminSuggestionController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new TreeSuggestion();
        $status = $_GET['status'] ?? 'pending';
        $this->view('admin.suggestions.index', [
            'user' => $user,
            'suggestions' => $model->all($status, 100),
            'currentStatus' => $status
        ]);
    }

    public function approve(string $id): void
    {
        $this->validateCsrf();
        $admin = $this->requireAdmin();
        $model = new TreeSuggestion();
        $suggestion = $model->findById((int)$id);
        if (!$suggestion) {
            $this->redirect('/admin/sugestoes');
            return;
        }
        $model->approve((int)$id, $admin['id']);

        // Dar pontos ao autor da sugestão
        $userModel = new User();
        $userModel->addPoints($suggestion['user_id'], POINTS_SUGGESTION_APPROVED);
        $logModel = new ContributionLog();
        $logModel->log($suggestion['user_id'], $suggestion['tree_id'], 'SUGGESTION_APPROVED', POINTS_SUGGESTION_APPROVED);

        // Notificar o autor
        $notif = new Notification();
        $notif->create(
            $suggestion['user_id'],
            'suggestion_approved',
            'Sugestão aprovada! 🎉',
            'Sua sugestão foi aprovada por um moderador. Você ganhou +' . POINTS_SUGGESTION_APPROVED . ' pontos!',
            '/arvore/' . $suggestion['tree_id']
        );

        $_SESSION['admin_success'] = 'Sugestão aprovada. +' . POINTS_SUGGESTION_APPROVED . ' pontos para o autor.';
        $this->redirect('/admin/sugestoes');
    }

    public function reject(string $id): void
    {
        $this->validateCsrf();
        $admin = $this->requireAdmin();
        $model = new TreeSuggestion();
        $model->reject((int)$id, $admin['id']);
        $_SESSION['admin_success'] = 'Sugestão rejeitada.';
        $this->redirect('/admin/sugestoes');
    }
}
