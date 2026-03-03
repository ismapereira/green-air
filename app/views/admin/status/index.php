<?php
$pageTitle = 'Status de preservação';
$user = $user ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Status de preservação</h1>
<div class="admin-form-inline">
    <form method="post" action="<?= BASE_URL ?>admin/status" class="form-inline">
        <input type="text" name="name" placeholder="Nome" required>
        <input type="text" name="description" placeholder="Descrição">
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>
<table class="admin-table">
    <thead><tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Ações</th></tr></thead>
    <tbody>
        <?php foreach ($statuses as $st): ?>
            <tr>
                <td><?= (int)$st['id'] ?></td>
                <td><?= htmlspecialchars($st['name']) ?></td>
                <td><?= htmlspecialchars($st['description'] ?? '') ?></td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>admin/status/editar/<?= (int)$st['id'] ?>" style="display:inline">
                        <input type="text" name="name" value="<?= htmlspecialchars($st['name']) ?>" required>
                        <input type="text" name="description" value="<?= htmlspecialchars($st['description'] ?? '') ?>">
                        <button type="submit" class="btn btn-small">Salvar</button>
                    </form>
                    <form method="post" action="<?= BASE_URL ?>admin/status/excluir/<?= (int)$st['id'] ?>" style="display:inline" onsubmit="return confirm('Excluir?');">
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
