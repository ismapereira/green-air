<?php
$pageTitle = 'Dashboard';
$user = $user ?? [];
$totalTrees = $totalTrees ?? 0;
$totalUsers = $totalUsers ?? 0;
$topContributors = $topContributors ?? [];
$riskCount = $riskCount ?? 0;
$pendingSuggestions = $pendingSuggestions ?? 0;
$bySpecies = $bySpecies ?? [];
$speciesNames = $speciesNames ?? [];
$byStatus = $byStatus ?? [];
$byNeighborhood = $byNeighborhood ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-4"><i class="bi bi-grid me-2"></i>Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card-gradient" style="background:var(--ga-gradient-card)">
            <span class="stat-icon"><i class="bi bi-tree"></i></span>
            <span class="stat-value"><?= (int)$totalTrees ?></span>
            <span class="stat-label">Árvores</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-gradient" style="background:linear-gradient(135deg,#3B82F6,#1D4ED8)">
            <span class="stat-icon"><i class="bi bi-people"></i></span>
            <span class="stat-value"><?= (int)$totalUsers ?></span>
            <span class="stat-label">Usuários</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-gradient" style="background:linear-gradient(135deg,#EF4444,#B91C1C)">
            <span class="stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
            <span class="stat-value"><?= (int)$riskCount ?></span>
            <span class="stat-label">Risco</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-gradient" style="background:var(--ga-gradient-gold)">
            <span class="stat-icon"><i class="bi bi-chat-square-text"></i></span>
            <span class="stat-value"><?= (int)$pendingSuggestions ?></span>
            <span class="stat-label">Sugestões</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Por Status</h6>
                <canvas id="chart-status" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Por Espécie (Top)</h6>
                <canvas id="chart-species" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Top Contribuidores</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>#</th><th>Nome</th><th>E-mail</th><th>Nível</th><th>Pontos</th></tr></thead>
                        <tbody>
                        <?php foreach ($topContributors as $i => $c): ?>
                            <tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($c['name']) ?></td><td class="text-muted small"><?= htmlspecialchars($c['email']) ?></td><td><?= htmlspecialchars($c['level_name'] ?? '') ?></td><td class="fw-bold"><?= (int)($c['points'] ?? 0) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Árvores por Bairro</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>Bairro</th><th>Total</th></tr></thead>
                        <tbody>
                        <?php foreach ($byNeighborhood as $row): ?>
                            <tr><td><?= htmlspecialchars($row['bairro'] ?? '-') ?></td><td class="fw-bold"><?= (int)($row['total'] ?? 0) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-cloud-sun me-2"></i>Clima</h6>
        <div id="admin-clima"></div>
    </div>
</div>

</div><!-- admin-content -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>assets/js/clima.js"></script>
<script>
(function() {
    var byStatus = <?= json_encode($byStatus) ?>;
    var bySpeciesRaw = <?= json_encode($bySpecies) ?>;
    var speciesNames = <?= json_encode($speciesNames) ?>;
    var colors = ['#22c55e','#eab308','#ef4444','#6b7280','#3b82f6','#8b5cf6'];
    if (document.getElementById('chart-status')) {
        new Chart(document.getElementById('chart-status'), {
            type: 'doughnut',
            data: { labels: Object.keys(byStatus), datasets: [{ data: Object.values(byStatus), backgroundColor: colors }] },
            options: { plugins: { legend: { position: 'bottom', labels: { color: '#94A3B8' } } } }
        });
    }
    var spLabels = bySpeciesRaw.map(function(r) { return speciesNames[r.species_id] || 'Outros'; });
    var spValues = bySpeciesRaw.map(function(r) { return parseInt(r.total, 10); });
    if (document.getElementById('chart-species') && spLabels.length) {
        new Chart(document.getElementById('chart-species'), {
            type: 'bar',
            data: { labels: spLabels, datasets: [{ label: 'Árvores', data: spValues, backgroundColor: '#10B981' }] },
            options: { scales: { y: { beginAtZero: true, ticks: { color: '#94A3B8' } }, x: { ticks: { color: '#94A3B8' } } }, plugins: { legend: { labels: { color: '#94A3B8' } } } }
        });
    }
    // Clima widget
    window.BASE_URL = <?= json_encode(BASE_URL) ?>;
    fetchClimaWidget('admin-clima', window.BASE_URL);
})();
</script>
</body></html>
