<?php
$pageTitle = 'Editar Árvore';
$user = $user ?? [];
$tree = $tree ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
$error = $error ?? null;
if (empty($tree)) { header('Location: ' . BASE_URL . 'minhas-arvores'); exit; }
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4" style="max-width:600px">
    <h4 class="fw-bold mb-3"><i class="bi bi-pencil text-success me-2"></i>Editar Árvore #<?= (int)$tree['id'] ?></h4>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>arvore/atualizar/<?= (int)$tree['id'] ?>" enctype="multipart/form-data" id="tree-form">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <input type="hidden" name="latitude" id="input-latitude" value="<?= htmlspecialchars($tree['latitude']) ?>">
        <input type="hidden" name="longitude" id="input-longitude" value="<?= htmlspecialchars($tree['longitude']) ?>">

        <div class="mb-3">
            <label class="form-label fw-bold">Foto</label>
            <div class="photo-upload-area">
                <?php if (!empty($tree['photo'])): ?>
                    <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($tree['photo']) ?>" class="preview-img">
                <?php else: ?>
                    <div class="upload-placeholder">
                        <i class="bi bi-camera text-muted" style="font-size:2rem"></i>
                        <p class="text-muted small mb-0">Alterar foto</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6">
                <label class="form-label fw-bold">Espécie *</label>
                <select name="species_id" id="species-select" class="form-select" required>
                    <?php foreach ($species as $s): ?>
                        <option value="<?= (int)$s['id'] ?>" <?= ((int)$tree['species_id'] === (int)$s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="unknown-species">
                    <label class="form-check-label small text-muted" for="unknown-species">
                        <i class="bi bi-question-circle me-1"></i>Não sei a espécie
                    </label>
                </div>
            </div>
            <div class="col-6">
                <label class="form-label fw-bold">Status *</label>
                <select name="status_id" class="form-select" required>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= (int)$st['id'] ?>" <?= ((int)$tree['status_id'] === (int)$st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Tamanho</label>
                <select name="size" class="form-select">
                    <option value="">-</option>
                    <option value="Pequeno" <?= ($tree['size'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
                    <option value="Médio" <?= ($tree['size'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option>
                    <option value="Grande" <?= ($tree['size'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Idade (anos)</label>
                <input type="number" name="age_approx" class="form-control" value="<?= htmlspecialchars($tree['age_approx'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Endereço</label>
                <input type="text" name="address" id="input-address" class="form-control" value="<?= htmlspecialchars($tree['address'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observations" class="form-control" rows="2"><?= htmlspecialchars($tree['observations'] ?? '') ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 mt-3 py-2 fw-bold">
            <i class="bi bi-check-lg me-2"></i>Salvar Alterações
        </button>
    </form>

    <a href="<?= BASE_URL ?>minhas-arvores" class="btn btn-outline-secondary w-100 mt-2"><i class="bi bi-arrow-left me-2"></i>Voltar</a>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
<script>
(function() {
    var select = document.getElementById('species-select');
    var checkbox = document.getElementById('unknown-species');
    if (!select || !checkbox) return;

    var unknownOption = null;
    for (var i = 0; i < select.options.length; i++) {
        var txt = select.options[i].text.normalize('NFC').toLowerCase();
        if (txt.indexOf('identificada') !== -1) {
            unknownOption = select.options[i];
            break;
        }
    }

    if (unknownOption && select.value === unknownOption.value) {
        checkbox.checked = true;
        select.disabled = true;
        select.style.opacity = '0.6';
    }

    checkbox.addEventListener('change', function() {
        if (this.checked && unknownOption) {
            select.value = unknownOption.value;
            select.disabled = true;
            select.style.opacity = '0.6';
        } else {
            select.disabled = false;
            select.style.opacity = '1';
            if (unknownOption && select.value === unknownOption.value) {
                select.value = '';
            }
        }
    });

    select.closest('form').addEventListener('submit', function() {
        select.disabled = false;
    });
})();
</script>
