<?php
$pageTitle = 'Sugestões';
$user = $user ?? [];
$suggestions = $suggestions ?? [];
$currentStatus = $currentStatus ?? 'pending';
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-chat-square-text me-2"></i>Sugestões de Atualização</h4>

<div class="d-flex gap-2 mb-3">
    <a href="<?= BASE_URL ?>admin/sugestoes?status=pending" class="btn btn-sm <?= $currentStatus==='pending'?'btn-success':'btn-outline-secondary' ?>">Pendentes</a>
    <a href="<?= BASE_URL ?>admin/sugestoes?status=approved" class="btn btn-sm <?= $currentStatus==='approved'?'btn-success':'btn-outline-secondary' ?>">Aprovadas</a>
    <a href="<?= BASE_URL ?>admin/sugestoes?status=rejected" class="btn btn-sm <?= $currentStatus==='rejected'?'btn-success':'btn-outline-secondary' ?>">Rejeitadas</a>
    <a href="<?= BASE_URL ?>admin/sugestoes?status=all" class="btn btn-sm <?= $currentStatus==='all'?'btn-success':'btn-outline-secondary' ?>">Todas</a>
</div>

<?php if (empty($suggestions)): ?>
    <div class="text-muted text-center py-4">Nenhuma sugestão encontrada.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Autor</th><th>Árvore</th><th>Sugestão</th><th>Status</th><th>Data</th><th>Ações</th></tr></thead>
        <tbody>
        <?php foreach ($suggestions as $s): ?>
            <tr>
                <td><?= (int)$s['id'] ?></td>
                <td class="small"><?= htmlspecialchars($s['user_name']) ?></td>
                <td><a href="<?= BASE_URL ?>arvore/<?= (int)$s['tree_id'] ?>" class="text-success">#<?= (int)$s['tree_id'] ?></a> <small><?= htmlspecialchars($s['species_name']??'') ?></small></td>
                <td class="small"><?= htmlspecialchars(mb_substr($s['suggestion'], 0, 80)) ?><?= mb_strlen($s['suggestion']) > 80 ? '...' : '' ?></td>
                <td>
                    <span class="badge bg-<?= $s['status']==='approved'?'success':($s['status']==='rejected'?'danger':'warning text-dark') ?>"><?= $s['status'] ?></span>
                </td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($s['created_at'])) ?></td>
                <td>
                    <?php if ($s['status'] === 'pending'): ?>
                    <form method="post" action="<?= BASE_URL ?>admin/sugestoes/aprovar/<?= (int)$s['id'] ?>" class="d-inline">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <button class="btn btn-sm btn-outline-success" title="Aprovar"><i class="bi bi-check-lg"></i></button>
                    </form>
                    <form method="post" action="<?= BASE_URL ?>admin/sugestoes/rejeitar/<?= (int)$s['id'] ?>" class="d-inline">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <button class="btn btn-sm btn-outline-danger" title="Rejeitar"><i class="bi bi-x-lg"></i></button>
                    </form>
                    <?php endif; ?>
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
