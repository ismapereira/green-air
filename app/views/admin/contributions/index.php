<?php
$pageTitle = 'Contribuições';
$user = $user ?? [];
$contributions = $contributions ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Log de contribuições</h1>
<table class="admin-table">
    <thead><tr><th>ID</th><th>Usuário</th><th>Ação</th><th>Pontos</th><th>Data</th></tr></thead>
    <tbody>
        <?php foreach ($contributions as $c): ?>
            <tr>
                <td><?= (int)$c['id'] ?></td>
                <td><?= htmlspecialchars($c['user_name'] ?? '') ?> (<?= htmlspecialchars($c['email'] ?? '') ?>)</td>
                <td><?= htmlspecialchars($c['action'] ?? '') ?></td>
                <td><?= (int)($c['points_awarded'] ?? 0) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($c['created_at'] ?? 'now')) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>
</body>
</html>
