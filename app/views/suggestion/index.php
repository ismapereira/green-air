<?php
$pageTitle = 'Minhas Sugestões';
require ROOT_PATH . '/app/views/layout/header.php';
$suggestions = $suggestions ?? [];
$categories = $categories ?? [];
$categoryColors = [
    'feature'     => 'primary',
    'species'     => 'success',
    'improvement' => 'info',
    'bug'         => 'danger',
    'other'       => 'secondary',
];
$categoryIcons = [
    'feature'     => 'bi-lightbulb',
    'species'     => 'bi-flower1',
    'improvement' => 'bi-arrow-up-circle',
    'bug'         => 'bi-bug',
    'other'       => 'bi-chat-dots',
];
$statusLabels = [
    'pending'     => ['Pendente', 'warning'],
    'reviewed'    => ['Em análise', 'info'],
    'accepted'    => ['Aceita', 'primary'],
    'implemented' => ['Implementada', 'success'],
    'rejected'    => ['Rejeitada', 'danger'],
];
?>

<div class="container py-4" style="max-width: 720px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-lightbulb me-2"></i>Sugestões</h2>
        <a href="<?= BASE_URL ?>sugestoes/nova" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>Nova Sugestão
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <p class="text-muted small mb-4">
        <i class="bi bi-people me-1"></i>Ajude a melhorar o Green Air! Envie ideias de novas funcionalidades, sugira espécies de árvores, reporte problemas ou proponha melhorias.
    </p>

    <!-- Categorias rápidas -->
    <div class="row g-2 mb-4">
        <?php foreach ($categories as $key => $label): ?>
        <div class="col">
            <div class="ga-glass-card text-center p-2 small">
                <i class="bi <?= $categoryIcons[$key] ?? 'bi-chat-dots' ?> d-block fs-5 text-<?= $categoryColors[$key] ?? 'secondary' ?> mb-1"></i>
                <span class="text-muted" style="font-size:0.7rem"><?= htmlspecialchars($label) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($suggestions)): ?>
        <div class="ga-glass-card text-center py-5">
            <i class="bi bi-chat-square-text d-block fs-1 text-muted mb-3"></i>
            <p class="text-muted mb-3">Você ainda não enviou nenhuma sugestão.</p>
            <a href="<?= BASE_URL ?>sugestoes/nova" class="btn btn-outline-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Enviar primeira sugestão
            </a>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($suggestions as $s): ?>
            <div class="ga-glass-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-<?= $categoryColors[$s['category']] ?? 'secondary' ?> bg-opacity-75">
                            <i class="bi <?= $categoryIcons[$s['category']] ?? 'bi-chat-dots' ?> me-1"></i><?= htmlspecialchars($categories[$s['category']] ?? $s['category']) ?>
                        </span>
                        <?php
                            $sl = $statusLabels[$s['status']] ?? ['?', 'secondary'];
                        ?>
                        <span class="badge bg-<?= $sl[1] ?><?= $s['status'] === 'pending' ? ' text-dark' : '' ?>"><?= $sl[0] ?></span>
                    </div>
                    <small class="text-muted"><?= date('d/m/Y', strtotime($s['created_at'])) ?></small>
                </div>
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($s['title']) ?></h6>
                <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars(mb_substr($s['description'], 0, 200))) ?><?= mb_strlen($s['description']) > 200 ? '...' : '' ?></p>
                <?php if (!empty($s['admin_response'])): ?>
                    <div class="mt-2 p-2 rounded" style="background: rgba(16,185,129,0.08); border-left: 3px solid var(--ga-primary)">
                        <small class="fw-bold text-success"><i class="bi bi-reply me-1"></i>Resposta da equipe:</small>
                        <p class="small mb-0 mt-1"><?= nl2br(htmlspecialchars($s['admin_response'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
