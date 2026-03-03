<?php
$pageTitle = 'Editar árvore';
$user = $user ?? [];
$tree = $tree ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
if (empty($tree)) { header('Location: ' . BASE_URL . 'admin/arvores'); exit; }
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Editar árvore #<?= (int)$tree['id'] ?></h1>
<form method="post" action="<?= BASE_URL ?>admin/arvores/editar/<?= (int)$tree['id'] ?>" enctype="multipart/form-data" class="admin-form">
    <label><span>Foto (opcional)</span><input type="file" name="photo" accept="image/*"><?php if (!empty($tree['photo'])): ?><br><img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($tree['photo']) ?>" alt="" class="thumb"><?php endif; ?></label>
    <label><span>Espécie</span><select name="species_id" required><?php foreach ($species as $s): ?><option value="<?= (int)$s['id'] ?>" <?= (int)$tree['species_id'] === (int)$s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option><?php endforeach; ?></select></label>
    <label><span>Status</span><select name="status_id" required><?php foreach ($statuses as $st): ?><option value="<?= (int)$st['id'] ?>" <?= (int)$tree['status_id'] === (int)$st['id'] ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option><?php endforeach; ?></select></label>
    <label><span>Latitude</span><input type="text" name="latitude" value="<?= htmlspecialchars($tree['latitude']) ?>"></label>
    <label><span>Longitude</span><input type="text" name="longitude" value="<?= htmlspecialchars($tree['longitude']) ?>"></label>
    <label><span>Endereço</span><input type="text" name="address" value="<?= htmlspecialchars($tree['address'] ?? '') ?>"></label>
    <label><span>Tamanho</span><select name="size"><option value="">-</option><option value="Pequeno" <?= ($tree['size'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option><option value="Médio" <?= ($tree['size'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option><option value="Grande" <?= ($tree['size'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option></select></label>
    <label><span>Idade (anos)</span><input type="number" name="age_approx" value="<?= htmlspecialchars($tree['age_approx'] ?? '') ?>"></label>
    <label><span>Observações</span><textarea name="observations" rows="3"><?= htmlspecialchars($tree['observations'] ?? '') ?></textarea></label>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<p><a href="<?= BASE_URL ?>admin/arvores">Voltar</a></p>
</main>
</body>
</html>
