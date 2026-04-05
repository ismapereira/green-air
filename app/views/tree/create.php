<?php
$pageTitle = 'Cadastrar Árvore';
$user = $user ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
$error = $error ?? null;
$old = $old ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4" style="max-width:600px">
    <h4 class="fw-bold mb-1"><i class="bi bi-tree text-success me-2"></i>Cadastrar Árvore</h4>
    <p class="text-muted small mb-3">Ative a localização para captura automática do GPS.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>cadastrar-arvore" enctype="multipart/form-data" id="tree-form">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <input type="hidden" name="latitude" id="input-latitude">
        <input type="hidden" name="longitude" id="input-longitude">

        <!-- Photo Upload -->
        <div class="mb-3">
            <label class="form-label fw-bold">Foto da árvore *</label>
            <div class="photo-upload-area">
                <div class="upload-placeholder">
                    <i class="bi bi-camera text-muted" style="font-size:2rem"></i>
                    <p class="text-muted small mb-0 mt-1">Toque para tirar foto ou selecionar</p>
                </div>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6">
                <label class="form-label fw-bold">Espécie *</label>
                <select name="species_id" id="species-select" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php foreach ($species as $s): ?>
                        <option value="<?= (int)$s['id'] ?>" <?= (($old['species_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
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
                    <option value="">Selecione</option>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= (int)$st['id'] ?>" <?= (($old['status_id'] ?? '') == $st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Tamanho</label>
                <select name="size" class="form-select">
                    <option value="">-</option>
                    <option value="Pequeno" <?= ($old['size'] ?? '') === 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
                    <option value="Médio" <?= ($old['size'] ?? '') === 'Médio' ? 'selected' : '' ?>>Médio</option>
                    <option value="Grande" <?= ($old['size'] ?? '') === 'Grande' ? 'selected' : '' ?>>Grande</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Idade (anos)</label>
                <input type="number" name="age_approx" class="form-control" min="0" value="<?= htmlspecialchars($old['age_approx'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Endereço</label>
                <input type="text" name="address" id="input-address" class="form-control" placeholder="Preenchido pelo GPS" value="<?= htmlspecialchars($old['address'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observations" class="form-control" rows="2" placeholder="Ex: próximo ao ponto de ônibus..."><?= htmlspecialchars($old['observations'] ?? '') ?></textarea>
            </div>
        </div>

        <div id="geo-status" class="mt-3 text-center">
            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>Obtendo localização...</small>
        </div>

        <button type="submit" class="btn btn-success w-100 mt-3 py-2 fw-bold" id="submit-tree">
            <i class="bi bi-tree me-2"></i>Cadastrar Árvore
        </button>
    </form>
</div>
<?php
$extraScripts = [BASE_URL . 'assets/js/geolocation.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
<script>
(function() {
    var select = document.getElementById('species-select');
    var checkbox = document.getElementById('unknown-species');
    if (!select || !checkbox) return;

    // Encontra a option "Não identificada"
    var unknownOption = null;
    for (var i = 0; i < select.options.length; i++) {
        var txt = select.options[i].text.normalize('NFC').toLowerCase();
        if (txt.indexOf('identificada') !== -1) {
            unknownOption = select.options[i];
            break;
        }
    }

    // Se a espécie já está selecionada como "Não identificada", marca o checkbox
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

    // Ao submeter, garante que o select não fique disabled (senão não envia)
    select.closest('form').addEventListener('submit', function() {
        select.disabled = false;
    });
})();
</script>
