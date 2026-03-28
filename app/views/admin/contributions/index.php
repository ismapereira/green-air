<?php
$pageTitle = 'Contribuições';
$user = $user ?? [];
$contributions = $contributions ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Log de Contribuições</h4>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Usuário</th><th>Ação</th><th>Pontos</th><th>Data</th></tr></thead>
        <tbody>
        <?php foreach ($contributions as $c): ?>
            <tr>
                <td><?= (int)$c['id'] ?></td>
                <td><?= htmlspecialchars($c['user_name']??'') ?> <small class="text-muted">(<?= htmlspecialchars($c['email']??'') ?>)</small></td>
                <td><?= htmlspecialchars($c['action']??'') ?></td>
                <td class="fw-bold"><?= (int)($c['points_awarded']??0) ?></td>
                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($c['created_at']??'now')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
