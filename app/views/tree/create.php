<?php
$pageTitle = 'Cadastrar árvore';
$user = $user ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
$error = $error ?? null;
$old = $old ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container form-page">
    <h1>Cadastrar árvore</h1>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <p class="hint">Ative a localização no navegador para capturar latitude/longitude automaticamente.</p>
    <form method="post" action="<?= BASE_URL ?>cadastrar-arvore" class="tree-form" enctype="multipart/form-data" id="tree-form">
        <input type="hidden" name="latitude" id="input-latitude">
        <input type="hidden" name="longitude" id="input-longitude">
        <label>
            <span>Foto da árvore *</span>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
        </label>
        <label>
            <span>Espécie *</span>
            <select name="species_id" required>
                <option value="">Selecione</option>
                <?php foreach ($species as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= (($old['species_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Status de preservação *</span>
            <select name="status_id" required>
                <option value="">Selecione</option>
                <?php foreach ($statuses as $st): ?>
                    <option value="<?= (int)$st['id'] ?>" <?= (($old['status_id'] ?? '') == $st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Tamanho aproximado</span>
            <select name="size">
                <option value="">Selecione</option>
                <option value="Pequeno" <?= ($old['size'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
                <option value="Médio" <?= ($old['size'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option>
                <option value="Grande" <?= ($old['size'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option>
            </select>
        </label>
        <label>
            <span>Idade aproximada (anos)</span>
            <input type="number" name="age_approx" min="0" value="<?= htmlspecialchars($old['age_approx'] ?? '') ?>">
        </label>
        <label>
            <span>Endereço aproximado</span>
            <input type="text" name="address" id="input-address" placeholder="Será preenchido pelo GPS se possível" value="<?= htmlspecialchars($old['address'] ?? '') ?>">
        </label>
        <label>
            <span>Observações</span>
            <textarea name="observations" rows="3"><?= htmlspecialchars($old['observations'] ?? '') ?></textarea>
        </label>
        <p id="geo-status" class="geo-status">Obtendo localização...</p>
        <button type="submit" class="btn btn-primary" id="submit-tree">Cadastrar árvore</button>
    </form>
</div>
<?php
$extraScripts = [BASE_URL . 'assets/js/geolocation.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
