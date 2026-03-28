<?php
$pageTitle = 'Mapa';
$species = $species ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="ga-map-page" style="position:relative">
    <div class="ga-map-filters">
        <select id="filter-species" class="form-select form-select-sm" style="width:auto;flex:1;min-width:100px">
            <option value="">Todas espécies</option>
            <?php foreach ($species as $s): ?>
                <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="filter-status" class="form-select form-select-sm" style="width:auto;flex:1;min-width:100px">
            <option value="">Todos status</option>
            <?php foreach ($statuses as $st): ?>
                <option value="<?= (int)$st['id'] ?>"><?= htmlspecialchars($st['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="filter-size" class="form-select form-select-sm d-none d-md-block" style="width:auto">
            <option value="">Tamanho</option>
            <option value="Pequeno">Pequeno</option>
            <option value="Médio">Médio</option>
            <option value="Grande">Grande</option>
        </select>
        <input type="text" id="filter-address" class="form-control form-control-sm d-none d-md-block" placeholder="Bairro..." style="width:auto;max-width:160px">
        <button id="filter-apply" class="btn btn-success btn-sm"><i class="bi bi-funnel me-1"></i>Filtrar</button>
    </div>

    <div id="map" class="ga-map-container"></div>

    <button id="btn-my-location" class="ga-fab" title="Minha localização">
        <i class="bi bi-crosshair"></i>
    </button>

    <!-- Tree Detail Modal -->
    <div class="modal fade" id="tree-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0" id="tree-modal-body"></div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<?php
$extraScripts = [BASE_URL . 'assets/js/map.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
