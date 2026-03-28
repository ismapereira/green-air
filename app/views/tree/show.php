<?php
$pageTitle = $tree['species_name'] ?? 'Árvore';
$tree = $tree ?? [];
$currentUser = $currentUser ?? null;
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4" style="max-width:700px">
    <a href="<?= BASE_URL ?>minhas-arvores" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Voltar</a>

    <?php if (!empty($tree['photo'])): ?>
        <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($tree['photo']) ?>" class="w-100 rounded-4 mb-3" style="max-height:350px;object-fit:cover" alt="">
    <?php endif; ?>

    <div class="d-flex align-items-start justify-content-between mb-3">
        <div>
            <h3 class="fw-bold mb-1"><?= htmlspecialchars($tree['species_name'] ?? '') ?></h3>
            <?php if (!empty($tree['scientific_name'])): ?>
                <small class="text-muted fst-italic"><?= htmlspecialchars($tree['scientific_name']) ?></small>
            <?php endif; ?>
        </div>
        <span class="badge badge-status badge-saudavel fs-6"><?= htmlspecialchars($tree['status_name'] ?? '') ?></span>
    </div>

    <div class="glass-card p-3 mb-3">
        <div class="row g-2 small">
            <?php if (!empty($tree['address'])): ?>
                <div class="col-12"><i class="bi bi-geo-alt text-success me-2"></i><strong>Endereço:</strong> <?= htmlspecialchars($tree['address']) ?></div>
            <?php endif; ?>
            <?php if (!empty($tree['size'])): ?>
                <div class="col-6"><i class="bi bi-rulers text-success me-2"></i><strong>Tamanho:</strong> <?= htmlspecialchars($tree['size']) ?></div>
            <?php endif; ?>
            <?php if (!empty($tree['age_approx'])): ?>
                <div class="col-6"><i class="bi bi-calendar text-success me-2"></i><strong>Idade:</strong> ~<?= (int)$tree['age_approx'] ?> anos</div>
            <?php endif; ?>
            <?php if (!empty($tree['observations'])): ?>
                <div class="col-12 mt-2"><i class="bi bi-chat-text text-success me-2"></i><strong>Observações:</strong> <?= htmlspecialchars($tree['observations']) ?></div>
            <?php endif; ?>
            <div class="col-12 mt-2 text-muted">
                <i class="bi bi-person me-1"></i>Cadastrado por <strong><?= htmlspecialchars($tree['user_name'] ?? '') ?></strong>
                <?php if (!empty($tree['created_at'])): ?> em <?= date('d/m/Y', strtotime($tree['created_at'])) ?><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mini Map -->
    <?php if (!empty($tree['latitude']) && !empty($tree['longitude'])): ?>
    <div id="mini-map" class="rounded-4 mb-3" style="height:200px"></div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function() {
        var m = L.map('mini-map', { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([<?= (float)$tree['latitude'] ?>, <?= (float)$tree['longitude'] ?>], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(m);
        L.marker([<?= (float)$tree['latitude'] ?>, <?= (float)$tree['longitude'] ?>]).addTo(m);
    })();
    </script>
    <?php endif; ?>

    <!-- Actions -->
    <div class="d-flex gap-2">
        <?php if ($currentUser && (int)($currentUser['id'] ?? 0) === (int)($tree['user_id'] ?? 0)): ?>
            <a href="<?= BASE_URL ?>arvore/editar/<?= (int)$tree['id'] ?>" class="btn btn-success flex-grow-1"><i class="bi bi-pencil me-1"></i>Editar</a>
        <?php endif; ?>
        <button class="btn btn-outline-success flex-grow-1" onclick="shareTree()"><i class="bi bi-share me-1"></i>Compartilhar</button>
    </div>
</div>

<script>
function shareTree() {
    if (navigator.share) {
        navigator.share({ title: '<?= addslashes($tree['species_name'] ?? 'Árvore') ?> - Green Air', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() { if (window.gaToast) gaToast('Link copiado!', 'success'); });
    }
}
</script>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
