<?php
$pageTitle = 'Editar árvore';
$user = $user ?? [];
$tree = $tree ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
$error = $error ?? null;
$old = $old ?? [];
if (empty($tree)) { header('Location: ' . BASE_URL . 'minhas-arvores'); exit; }
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container form-page">
    <h1>Editar árvore</h1>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>arvore/atualizar/<?= (int)$tree['id'] ?>" class="tree-form" enctype="multipart/form-data" id="tree-form">
        <input type="hidden" name="latitude" id="input-latitude" value="<?= htmlspecialchars($tree['latitude']) ?>">
        <input type="hidden" name="longitude" id="input-longitude" value="<?= htmlspecialchars($tree['longitude']) ?>">
        <label>
            <span>Foto (deixe em branco para manter)</span>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            <?php if (!empty($tree['photo'])): ?><br><img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($tree['photo']) ?>" alt="" class="thumb"><?php endif; ?>
        </label>
        <label>
            <span>Espécie *</span>
            <select name="species_id" required>
                <?php foreach ($species as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= ((int)$tree['species_id']) === ((int)$s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Status *</span>
            <select name="status_id" required>
                <?php foreach ($statuses as $st): ?>
                    <option value="<?= (int)$st['id'] ?>" <?= ((int)$tree['status_id']) === ((int)$st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Tamanho</span>
            <select name="size">
                <option value="">Selecione</option>
                <option value="Pequeno" <?= ($tree['size'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
                <option value="Médio" <?= ($tree['size'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option>
                <option value="Grande" <?= ($tree['size'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option>
            </select>
        </label>
        <label>
            <span>Idade (anos)</span>
            <input type="number" name="age_approx" min="0" value="<?= htmlspecialchars($tree['age_approx'] ?? '') ?>">
        </label>
        <label>
            <span>Endereço</span>
            <input type="text" name="address" id="input-address" value="<?= htmlspecialchars($tree['address'] ?? '') ?>">
        </label>
        <label>
            <span>Observações</span>
            <textarea name="observations" rows="3"><?= htmlspecialchars($tree['observations'] ?? '') ?></textarea>
        </label>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
