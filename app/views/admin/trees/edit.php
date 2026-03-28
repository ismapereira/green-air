<?php
$pageTitle = 'Editar árvore';
$user = $user ?? [];
$tree = $tree ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
if (empty($tree)) { header('Location: ' . BASE_URL . 'admin/arvores'); exit; }
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-pencil me-2"></i>Editar Árvore #<?= (int)$tree['id'] ?></h4>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>admin/arvores/editar/<?= (int)$tree['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                <?php if (!empty($tree['photo'])): ?><img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($tree['photo']) ?>" class="mt-2 rounded" style="max-width:120px"><?php endif; ?>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Espécie</label>
                    <select name="species_id" class="form-select form-select-sm" required>
                        <?php foreach ($species as $s): ?><option value="<?= (int)$s['id'] ?>" <?= (int)$tree['species_id']===(int)$s['id']?'selected':'' ?>><?= htmlspecialchars($s['name']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-select form-select-sm" required>
                        <?php foreach ($statuses as $st): ?><option value="<?= (int)$st['id'] ?>" <?= (int)$tree['status_id']===(int)$st['id']?'selected':'' ?>><?= htmlspecialchars($st['name']) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6"><label class="form-label">Latitude</label><input type="text" name="latitude" class="form-control form-control-sm" value="<?= htmlspecialchars($tree['latitude']) ?>"></div>
                <div class="col-6"><label class="form-label">Longitude</label><input type="text" name="longitude" class="form-control form-control-sm" value="<?= htmlspecialchars($tree['longitude']) ?>"></div>
            </div>
            <div class="mb-3"><label class="form-label">Endereço</label><input type="text" name="address" class="form-control form-control-sm" value="<?= htmlspecialchars($tree['address'] ?? '') ?>"></div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Tamanho</label>
                    <select name="size" class="form-select form-select-sm">
                        <option value="">-</option>
                        <option value="Pequeno" <?= ($tree['size']??'')==='Pequeno'?'selected':'' ?>>Pequeno</option>
                        <option value="Médio" <?= ($tree['size']??'')==='Médio'?'selected':'' ?>>Médio</option>
                        <option value="Grande" <?= ($tree['size']??'')==='Grande'?'selected':'' ?>>Grande</option>
                    </select>
                </div>
                <div class="col-6"><label class="form-label">Idade</label><input type="number" name="age_approx" class="form-control form-control-sm" value="<?= htmlspecialchars($tree['age_approx']??'') ?>"></div>
            </div>
            <div class="mb-3"><label class="form-label">Observações</label><textarea name="observations" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($tree['observations']??'') ?></textarea></div>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Salvar</button>
            <a href="<?= BASE_URL ?>admin/arvores" class="btn btn-outline-secondary btn-sm">Voltar</a>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
