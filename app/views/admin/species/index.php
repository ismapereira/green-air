<?php
$pageTitle = 'Espécies';
$user = $user ?? [];
$species = $species ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Espécies</h1>
<div class="admin-form-inline">
    <form method="post" action="<?= BASE_URL ?>admin/especies" class="form-inline">
        <input type="text" name="name" placeholder="Nome" required>
        <input type="text" name="scientific_name" placeholder="Nome científico">
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>
<table class="admin-table">
    <thead><tr><th>ID</th><th>Nome</th><th>Nome científico</th><th>Ações</th></tr></thead>
    <tbody>
        <?php foreach ($species as $s): ?>
            <tr>
                <td><?= (int)$s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['scientific_name'] ?? '') ?></td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>admin/especies/editar/<?= (int)$s['id'] ?>" style="display:inline">
                        <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" required>
                        <input type="text" name="scientific_name" value="<?= htmlspecialchars($s['scientific_name'] ?? '') ?>">
                        <button type="submit" class="btn btn-small">Salvar</button>
                    </form>
                    <form method="post" action="<?= BASE_URL ?>admin/especies/excluir/<?= (int)$s['id'] ?>" style="display:inline" onsubmit="return confirm('Excluir?');">
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
