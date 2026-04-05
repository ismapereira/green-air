<?php
$pageTitle = 'Sugestão #' . ($suggestion['id'] ?? '');
$user = $user ?? [];
$suggestion = $suggestion ?? [];
$categories = $categories ?? [];
$statuses = $statuses ?? [];
$categoryColors = [
    'feature' => 'primary', 'species' => 'success', 'improvement' => 'info',
    'bug' => 'danger', 'other' => 'secondary'
];
$categoryIcons = [
    'feature' => 'bi-lightbulb', 'species' => 'bi-flower1', 'improvement' => 'bi-arrow-up-circle',
    'bug' => 'bi-bug', 'other' => 'bi-chat-dots'
];
$statusColors = [
    'pending' => 'warning', 'reviewed' => 'info', 'accepted' => 'primary',
    'implemented' => 'success', 'rejected' => 'danger'
];
require ROOT_PATH . '/app/views/admin/layout.php';
?>

<a href="<?= BASE_URL ?>admin/comunidade" class="btn btn-sm btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left me-1"></i>Voltar
</a>

<div class="row g-4">
    <!-- Detalhes da sugestão -->
    <div class="col-lg-7">
        <div class="card bg-dark bg-opacity-50 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-<?= $categoryColors[$suggestion['category']] ?? 'secondary' ?> bg-opacity-75 mb-2">
                            <i class="bi <?= $categoryIcons[$suggestion['category']] ?? 'bi-chat-dots' ?> me-1"></i><?= htmlspecialchars($categories[$suggestion['category']] ?? $suggestion['category']) ?>
                        </span>
                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($suggestion['title']) ?></h5>
                    </div>
                    <span class="badge bg-<?= $statusColors[$suggestion['status']] ?? 'secondary' ?><?= $suggestion['status'] === 'pending' ? ' text-dark' : '' ?> fs-6">
                        <?= htmlspecialchars($statuses[$suggestion['status']] ?? $suggestion['status']) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-person me-1"></i><?= htmlspecialchars($suggestion['user_name']) ?>
                        (<?= htmlspecialchars($suggestion['user_email']) ?>)
                        <span class="ms-2"><i class="bi bi-calendar me-1"></i><?= date('d/m/Y H:i', strtotime($suggestion['created_at'])) ?></span>
                    </small>
                </div>

                <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06)">
                    <p class="mb-0" style="white-space: pre-wrap"><?= htmlspecialchars($suggestion['description']) ?></p>
                </div>

                <?php if (!empty($suggestion['admin_response'])): ?>
                <div class="mt-3 p-3 rounded" style="background: rgba(16,185,129,0.08); border-left: 3px solid #10B981">
                    <small class="fw-bold text-success"><i class="bi bi-reply me-1"></i>Resposta anterior:</small>
                    <p class="mb-0 mt-1 small" style="white-space: pre-wrap"><?= htmlspecialchars($suggestion['admin_response']) ?></p>
                    <?php if (!empty($suggestion['reviewer_name'])): ?>
                        <small class="text-muted mt-1 d-block">
                            Por <?= htmlspecialchars($suggestion['reviewer_name']) ?> em <?= date('d/m/Y H:i', strtotime($suggestion['reviewed_at'])) ?>
                        </small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Formulário de resposta -->
    <div class="col-lg-5">
        <div class="card bg-dark bg-opacity-50 border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold"><i class="bi bi-reply me-1"></i>Responder / Atualizar Status</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= BASE_URL ?>admin/comunidade/responder/<?= (int)$suggestion['id'] ?>">
                    <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Novo status *</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <?php foreach ($statuses as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $suggestion['status'] === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Resposta para o usuário</label>
                        <textarea name="admin_response" class="form-control form-control-sm" rows="4" placeholder="Escreva uma resposta que será visível ao usuário..."><?= htmlspecialchars($suggestion['admin_response'] ?? '') ?></textarea>
                        <div class="form-text">Opcional. O usuário receberá uma notificação.</div>
                    </div>

                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-check-lg me-1"></i>Salvar
                    </button>
                </form>
            </div>
        </div>

        <form method="post" action="<?= BASE_URL ?>admin/comunidade/excluir/<?= (int)$suggestion['id'] ?>" class="mt-3" onsubmit="return confirm('Tem certeza? Esta ação é irreversível.')">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <button class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i>Excluir sugestão</button>
        </form>
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
