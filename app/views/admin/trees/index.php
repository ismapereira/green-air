<?php
$pageTitle = 'Árvores';
$user = $user ?? [];
$trees = $trees ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Árvores</h1>
<form method="get" class="admin-filters">
    <select name="species_id"><option value="">Todas espécies</option><?php foreach ($species as $s): ?><option value="<?= (int)$s['id'] ?>" <?= (isset($_GET['species_id']) && (int)$_GET['species_id'] === (int)$s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option><?php endforeach; ?></select>
    <select name="status_id"><option value="">Todos status</option><?php foreach ($statuses as $st): ?><option value="<?= (int)$st['id'] ?>" <?= (isset($_GET['status_id']) && (int)$_GET['status_id'] === (int)$st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option><?php endforeach; ?></select>
    <button type="submit" class="btn btn-secondary">Filtrar</button>
</form>
<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Foto</th><th>Espécie</th><th>Status</th><th>Endereço</th><th>Cadastrante</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php foreach ($trees as $t): ?>
            <tr>
                <td><?= (int)$t['id'] ?></td>
                <td><?php if (!empty($t['photo'])): ?><img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="" class="thumb-sm"><?php endif; ?></td>
                <td><?= htmlspecialchars($t['species_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($t['status_name'] ?? '') ?></td>
                <td><?= htmlspecialchars(mb_substr($t['address'] ?? '', 0, 40)) ?></td>
                <td><?= htmlspecialchars($t['user_name'] ?? '') ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/arvores/editar/<?= (int)$t['id'] ?>" class="btn btn-small">Editar</a>
                    <form method="post" action="<?= BASE_URL ?>admin/arvores/excluir/<?= (int)$t['id'] ?>" style="display:inline" onsubmit="return confirm('Excluir esta árvore?');">
                        <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>
</body>
</html>
