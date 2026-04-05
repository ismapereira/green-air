<?php
$pageTitle = 'Sugestões da Comunidade';
$user = $user ?? [];
$suggestions = $suggestions ?? [];
$currentStatus = $currentStatus ?? 'pending';
$currentCategory = $currentCategory ?? 'all';
$categories = $categories ?? [];
$statuses = $statuses ?? [];
$stats = $stats ?? [];
$pendingCount = $pendingCount ?? 0;

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
$statusColors = [
    'pending'     => 'warning',
    'reviewed'    => 'info',
    'accepted'    => 'primary',
    'implemented' => 'success',
    'rejected'    => 'danger',
];

require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3">
    <i class="bi bi-people me-2"></i>Sugestões da Comunidade
    <?php if ($pendingCount > 0): ?>
        <span class="badge bg-warning text-dark ms-2"><?= $pendingCount ?> pendentes</span>
    <?php endif; ?>
</h4>

<!-- Stats por categoria -->
<?php if (!empty($stats)): ?>
<div class="row g-2 mb-3">
    <?php foreach ($stats as $st): ?>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2 bg-dark bg-opacity-50 rounded-3 px-3 py-2 small">
            <i class="bi <?= $categoryIcons[$st['category']] ?? 'bi-chat-dots' ?> text-<?= $categoryColors[$st['category']] ?? 'secondary' ?>"></i>
            <span><?= htmlspecialchars($categories[$st['category']] ?? $st['category']) ?></span>
            <span class="badge bg-secondary"><?= (int)$st['total'] ?></span>
            <?php if ((int)$st['pending'] > 0): ?>
                <span class="badge bg-warning text-dark"><?= (int)$st['pending'] ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Filtros -->
<div class="d-flex flex-wrap gap-2 mb-3">
    <div>
        <small class="text-muted d-block mb-1">Status:</small>
        <div class="d-flex gap-1 flex-wrap">
            <a href="<?= BASE_URL ?>admin/comunidade?status=pending&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' ?>">Pendentes</a>
            <a href="<?= BASE_URL ?>admin/comunidade?status=reviewed&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'reviewed' ? 'btn-info' : 'btn-outline-secondary' ?>">Em análise</a>
            <a href="<?= BASE_URL ?>admin/comunidade?status=accepted&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'accepted' ? 'btn-primary' : 'btn-outline-secondary' ?>">Aceitas</a>
            <a href="<?= BASE_URL ?>admin/comunidade?status=implemented&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'implemented' ? 'btn-success' : 'btn-outline-secondary' ?>">Implementadas</a>
            <a href="<?= BASE_URL ?>admin/comunidade?status=rejected&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' ?>">Rejeitadas</a>
            <a href="<?= BASE_URL ?>admin/comunidade?status=all&category=<?= $currentCategory ?>" class="btn btn-sm <?= $currentStatus === 'all' ? 'btn-success' : 'btn-outline-secondary' ?>">Todas</a>
        </div>
    </div>
    <div class="ms-md-3">
        <small class="text-muted d-block mb-1">Categoria:</small>
        <div class="d-flex gap-1 flex-wrap">
            <a href="<?= BASE_URL ?>admin/comunidade?status=<?= $currentStatus ?>&category=all" class="btn btn-sm <?= $currentCategory === 'all' ? 'btn-success' : 'btn-outline-secondary' ?>">Todas</a>
            <?php foreach ($categories as $key => $label): ?>
                <a href="<?= BASE_URL ?>admin/comunidade?status=<?= $currentStatus ?>&category=<?= $key ?>" class="btn btn-sm <?= $currentCategory === $key ? 'btn-' . $categoryColors[$key] : 'btn-outline-secondary' ?>">
                    <i class="bi <?= $categoryIcons[$key] ?> me-1"></i><?= htmlspecialchars($label) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (empty($suggestions)): ?>
    <div class="text-muted text-center py-4">Nenhuma sugestão encontrada.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-sm table-hover align-middle">
        <thead>
            <tr>
                <th width="40">ID</th>
                <th>Categoria</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Status</th>
                <th>Data</th>
                <th width="120">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($suggestions as $s): ?>
            <tr>
                <td class="text-muted small"><?= (int)$s['id'] ?></td>
                <td>
                    <span class="badge bg-<?= $categoryColors[$s['category']] ?? 'secondary' ?> bg-opacity-75">
                        <i class="bi <?= $categoryIcons[$s['category']] ?? 'bi-chat-dots' ?> me-1"></i><?= htmlspecialchars($categories[$s['category']] ?? $s['category']) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>admin/comunidade/<?= (int)$s['id'] ?>" class="text-decoration-none fw-medium">
                        <?= htmlspecialchars(mb_substr($s['title'], 0, 60)) ?><?= mb_strlen($s['title']) > 60 ? '...' : '' ?>
                    </a>
                </td>
                <td class="small"><?= htmlspecialchars($s['user_name']) ?></td>
                <td>
                    <span class="badge bg-<?= $statusColors[$s['status']] ?? 'secondary' ?><?= $s['status'] === 'pending' ? ' text-dark' : '' ?>">
                        <?= htmlspecialchars($statuses[$s['status']] ?? $s['status']) ?>
                    </span>
                </td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($s['created_at'])) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/comunidade/<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-info" title="Ver detalhes"><i class="bi bi-eye"></i></a>
                    <form method="post" action="<?= BASE_URL ?>admin/comunidade/excluir/<?= (int)$s['id'] ?>" class="d-inline" onsubmit="return confirm('Excluir esta sugestão?')">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <button class="btn btn-sm btn-outline-danger" title="Excluir"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
