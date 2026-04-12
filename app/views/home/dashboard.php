<?php
$pageTitle = 'Estatísticas';
$totalTrees = $totalTrees ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalSpecies = $totalSpecies ?? 0;
$topContributors = $topContributors ?? [];
$bySpecies = $bySpecies ?? [];
$byNeighborhood = $byNeighborhood ?? [];
$speciesList = $speciesList ?? [];
$recentTrees = $recentTrees ?? [];

// Mapear IDs de espécies para nomes
$speciesMap = [];
foreach ($speciesList as $sp) $speciesMap[$sp['id']] = $sp['name'];

require ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Hero Stats -->
<section class="py-5" style="background: var(--ga-gradient-hero)">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2" data-aos="fade-up"><i class="bi bi-bar-chart-line me-2"></i>Estatísticas</h1>
        <p class="lead mb-4 opacity-75" data-aos="fade-up" data-aos-delay="100">Dados abertos sobre a arborização urbana mapeada pela comunidade</p>
        <div class="row g-3 justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4" style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                    <div class="fs-2 fw-bold"><?= number_format($totalTrees) ?></div>
                    <small class="opacity-75"><i class="bi bi-tree me-1"></i>Árvores</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4" style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                    <div class="fs-2 fw-bold"><?= number_format($totalUsers) ?></div>
                    <small class="opacity-75"><i class="bi bi-people me-1"></i>Contribuidores</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4" style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                    <div class="fs-2 fw-bold"><?= number_format($totalSpecies) ?></div>
                    <small class="opacity-75"><i class="bi bi-flower1 me-1"></i>Espécies</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4" style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                    <div class="fs-2 fw-bold"><?= count($byNeighborhood) ?></div>
                    <small class="opacity-75"><i class="bi bi-geo-alt me-1"></i>Bairros</small>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row g-4">

        <!-- Gráfico: Árvores por Espécie -->
        <div class="col-md-7" data-aos="fade-up">
            <div class="ga-glass-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-flower1 text-success me-2"></i>Árvores por Espécie</h5>
                <?php if (!empty($bySpecies)): ?>
                    <canvas id="chartSpecies" height="300"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Nenhuma árvore cadastrada ainda.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Contribuidores -->
        <div class="col-md-5" data-aos="fade-up" data-aos-delay="100">
            <div class="ga-glass-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-trophy text-warning me-2"></i>Top Contribuidores</h5>
                <?php if (empty($topContributors)): ?>
                    <p class="text-muted text-center py-4">Sem contribuidores ainda.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($topContributors as $i => $c): ?>
                        <div class="d-flex align-items-center gap-3 p-2 rounded-3" style="background:var(--ga-bg-card)">
                            <span class="fw-bold text-muted" style="width:28px;text-align:center">
                                <?php if ($i === 0): ?><i class="bi bi-trophy-fill text-warning"></i>
                                <?php elseif ($i === 1): ?><i class="bi bi-trophy-fill" style="color:#C0C0C0"></i>
                                <?php elseif ($i === 2): ?><i class="bi bi-trophy-fill" style="color:#CD7F32"></i>
                                <?php else: ?><?= $i + 1 ?>º<?php endif; ?>
                            </span>
                            <?php if (!empty($c['photo'])): ?>
                                <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($c['photo']) ?>" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;background:var(--ga-primary);color:#fff;font-size:0.85rem"><?= strtoupper(mb_substr($c['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?= htmlspecialchars($c['name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($c['level_name'] ?? 'Bronze') ?></small>
                            </div>
                            <span class="badge bg-success-subtle text-success"><?= (int)($c['points'] ?? 0) ?> pts</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Gráfico: Árvores por Bairro -->
        <div class="col-md-5" data-aos="fade-up">
            <div class="ga-glass-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt text-info me-2"></i>Top Bairros</h5>
                <?php if (!empty($byNeighborhood)): ?>
                    <canvas id="chartNeighborhood" height="280"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Nenhum dado de bairro disponível.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Últimas Árvores -->
        <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">
            <div class="ga-glass-card p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Últimas Árvores Cadastradas</h5>
                <?php if (empty($recentTrees)): ?>
                    <p class="text-muted text-center py-4">Nenhuma árvore cadastrada ainda.</p>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach (array_slice($recentTrees, 0, 6) as $t): ?>
                        <div class="col-6 col-md-4">
                            <div class="rounded-3 overflow-hidden" style="background:var(--ga-bg-card)">
                                <?php if (!empty($t['photo'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="" style="width:100%;height:100px;object-fit:cover">
                                <?php else: ?>
                                    <div style="height:100px;display:flex;align-items:center;justify-content:center;background:rgba(16,185,129,0.05)">
                                        <i class="bi bi-tree text-success fs-3"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="p-2">
                                    <div class="fw-bold small text-truncate"><?= htmlspecialchars($t['species_name'] ?? '') ?></div>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($t['created_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <?php if (!($currentUser ?? null)): ?>
    <div class="text-center py-5" data-aos="fade-up">
        <h3 class="fw-bold mb-3">Faça parte dessa mudança <i class="bi bi-leaf" style="color:var(--ga-primary)"></i></h3>
        <p class="text-muted mb-4">Junte-se à comunidade e ajude a mapear as árvores da sua cidade.</p>
        <a href="<?= BASE_URL ?>registro" class="btn btn-success btn-lg"><i class="bi bi-person-plus me-2"></i>Criar Conta Gratuita</a>
    </div>
    <?php endif; ?>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cores do design system
    var colors = ['#10B981','#059669','#047857','#3B82F6','#8B5CF6','#EC4899','#F59E0B','#EF4444','#14B8A6','#6366F1','#F97316','#84CC16'];

    <?php if (!empty($bySpecies)): ?>
    // Gráfico de espécies (barras horizontais)
    var speciesLabels = <?= json_encode(array_map(function($s) use ($speciesMap) { return $speciesMap[$s['species_id']] ?? 'ID ' . $s['species_id']; }, array_slice($bySpecies, 0, 10))) ?>;
    var speciesData = <?= json_encode(array_map(function($s) { return (int)$s['total']; }, array_slice($bySpecies, 0, 10))) ?>;

    new Chart(document.getElementById('chartSpecies'), {
        type: 'bar',
        data: {
            labels: speciesLabels,
            datasets: [{
                label: 'Árvores',
                data: speciesData,
                backgroundColor: colors.slice(0, speciesLabels.length),
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(148,163,184,0.1)' }, ticks: { color: '#94A3B8' } },
                y: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } }
            }
        }
    });
    <?php endif; ?>

    <?php if (!empty($byNeighborhood)): ?>
    // Gráfico de bairros (donut)
    var nhLabels = <?= json_encode(array_map(function($n) { return $n['bairro'] ?: 'Sem bairro'; }, array_slice($byNeighborhood, 0, 8))) ?>;
    var nhData = <?= json_encode(array_map(function($n) { return (int)$n['total']; }, array_slice($byNeighborhood, 0, 8))) ?>;

    new Chart(document.getElementById('chartNeighborhood'), {
        type: 'doughnut',
        data: {
            labels: nhLabels,
            datasets: [{
                data: nhData,
                backgroundColor: colors.slice(0, nhLabels.length),
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94A3B8', font: { size: 11 }, padding: 12, usePointStyle: true }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
