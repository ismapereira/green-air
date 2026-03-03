<?php
$pageTitle = 'Dashboard';
$user = $user ?? [];
$totalTrees = $totalTrees ?? 0;
$totalUsers = $totalUsers ?? 0;
$topContributors = $topContributors ?? [];
$riskCount = $riskCount ?? 0;
$bySpecies = $bySpecies ?? [];
$speciesNames = $speciesNames ?? [];
$byStatus = $byStatus ?? [];
$byNeighborhood = $byNeighborhood ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Dashboard administrativo</h1>
<div class="admin-stats">
    <div class="stat-card"><span class="stat-value"><?= (int)$totalTrees ?></span><span class="stat-label">Árvores</span></div>
    <div class="stat-card"><span class="stat-value"><?= (int)$totalUsers ?></span><span class="stat-label">Usuários</span></div>
    <div class="stat-card stat-warning"><span class="stat-value"><?= (int)$riskCount ?></span><span class="stat-label">Risco de queda</span></div>
</div>
<section class="admin-section">
    <h2>Gráficos</h2>
    <div class="charts-row">
        <div class="chart-box">
            <h3>Por status</h3>
            <canvas id="chart-status" width="300" height="200"></canvas>
        </div>
        <div class="chart-box">
            <h3>Por espécie (top)</h3>
            <canvas id="chart-species" width="300" height="200"></canvas>
        </div>
    </div>
</section>
<section class="admin-section">
    <h2>Top contribuidores</h2>
    <table class="admin-table">
        <thead><tr><th>#</th><th>Nome</th><th>E-mail</th><th>Nível</th><th>Pontos</th></tr></thead>
        <tbody>
            <?php foreach ($topContributors as $i => $c): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['level_name'] ?? '') ?></td>
                    <td><?= (int)($c['points'] ?? 0) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<section class="admin-section">
    <h2>Árvores por bairro</h2>
    <table class="admin-table">
        <thead><tr><th>Bairro</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($byNeighborhood as $row): ?>
                <tr><td><?= htmlspecialchars($row['bairro'] ?? '') ?></td><td><?= (int)($row['total'] ?? 0) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<section class="admin-section">
    <h2>Clima</h2>
    <div id="admin-clima" class="clima-widget">Carregando...</div>
</section>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    var byStatus = <?= json_encode($byStatus) ?>;
    var bySpeciesRaw = <?= json_encode($bySpecies) ?>;
    var speciesNames = <?= json_encode($speciesNames) ?>;
    var labelsStatus = Object.keys(byStatus);
    var valuesStatus = Object.values(byStatus);
    if (document.getElementById('chart-status')) {
        new Chart(document.getElementById('chart-status'), {
            type: 'doughnut',
            data: {
                labels: labelsStatus,
                datasets: [{ data: valuesStatus, backgroundColor: ['#22c55e','#eab308','#ef4444','#6b7280'] }]
            }
        });
    }
    var spLabels = bySpeciesRaw.map(function(r) { return speciesNames[r.species_id] || 'Outros'; });
    var spValues = bySpeciesRaw.map(function(r) { return parseInt(r.total, 10); });
    if (document.getElementById('chart-species') && spLabels.length) {
        new Chart(document.getElementById('chart-species'), {
            type: 'bar',
            data: {
                labels: spLabels,
                datasets: [{ label: 'Árvores', data: spValues, backgroundColor: '#16a34a' }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    }
})();
</script>
<script src="<?= BASE_URL ?>assets/js/clima.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var w = document.getElementById('admin-clima');
    if (!w) return;
    var base = '<?= BASE_URL ?>';
    function show(d) {
        if (d.error) { w.innerHTML = d.error; return; }
        w.innerHTML = '<p><strong>' + (d.city || '') + '</strong>: ' + (d.temp != null ? d.temp + '°C' : '-') + ', Umidade: ' + (d.humidity != null ? d.humidity + '%' : '-') + '</p><p>AQI: ' + (d.aqi != null ? d.aqi : '-') + '</p>';
    }
    function fetchClima(params) {
        var qs = params ? '?' + new URLSearchParams(params).toString() : '';
        fetch(base + 'api/clima' + qs).then(function(r) { return r.json(); }).then(show).catch(function() { w.innerHTML = 'Erro ao carregar clima.'; });
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) { fetchClima({ lat: pos.coords.latitude, lon: pos.coords.longitude }); },
            function() { fetchClima(); },
            { timeout: 8000 }
        );
    } else {
        fetchClima();
    }
});
</script>
</body>
</html>
