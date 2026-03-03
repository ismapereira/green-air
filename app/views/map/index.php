<?php
$pageTitle = 'Mapa';
$species = $species ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="map-page">
    <div class="map-filters">
        <h2>Filtros</h2>
        <label>Espécie <select id="filter-species"><option value="">Todas</option><?php foreach ($species as $s): ?><option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option><?php endforeach; ?></select></label>
        <label>Status <select id="filter-status"><option value="">Todos</option><?php foreach ($statuses as $st): ?><option value="<?= (int)$st['id'] ?>"><?= htmlspecialchars($st['name']) ?></option><?php endforeach; ?></select></label>
        <label>Tamanho <select id="filter-size"><option value="">Todos</option><option value="Pequeno">Pequeno</option><option value="Médio">Médio</option><option value="Grande">Grande</option></select></label>
        <label>Bairro <input type="text" id="filter-address" placeholder="Buscar por endereço"></label>
        <button type="button" id="filter-apply" class="btn btn-primary">Aplicar</button>
        <button type="button" id="btn-my-location" class="btn btn-secondary" title="Centralizar na minha localização">📍 Minha localização</button>
    </div>
    <div id="map" class="map-container"></div>
    <div id="tree-modal" class="modal" hidden>
        <div class="modal-content">
            <button type="button" class="modal-close" aria-label="Fechar">&times;</button>
            <div id="tree-modal-body"></div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<?php
$extraScripts = [BASE_URL . 'assets/js/map.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
