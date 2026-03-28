<?php
$pageTitle = 'Painel';
$user = $user ?? [];
$myTrees = $myTrees ?? [];
$topContributors = $topContributors ?? [];
$levelProgress = $levelProgress ?? [];
$totalTreesGlobal = $totalTreesGlobal ?? 0;
$totalUsersGlobal = $totalUsersGlobal ?? 0;
require ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <!-- Welcome -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Olá, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>! 👋</h4>
            <small class="text-muted"><?= htmlspecialchars($user['level_name'] ?? 'Bronze') ?> · <?= (int)($user['points'] ?? 0) ?> pontos</small>
        </div>
        <a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-success d-none d-md-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>Cadastrar
        </a>
    </div>

    <!-- Level Progress -->
    <?php if (!empty($levelProgress['next'])): ?>
    <div class="glass-card p-3 mb-4" data-aos="fade-up">
        <div class="d-flex justify-content-between small mb-1">
            <span class="fw-bold"><?= htmlspecialchars($levelProgress['current']['name'] ?? '') ?></span>
            <span class="text-muted"><?= htmlspecialchars($levelProgress['next']['name'] ?? '') ?> (faltam <?= $levelProgress['points_to_next'] ?> pts)</span>
        </div>
        <div class="progress" style="height:8px">
            <div class="progress-bar bg-success" style="width:<?= $levelProgress['progress'] ?>%"></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-gradient" style="background:var(--ga-gradient-card)">
                <span class="stat-icon"><i class="bi bi-tree"></i></span>
                <span class="stat-value"><?= count($myTrees) ?></span>
                <span class="stat-label">Minhas Árvores</span>
            </div>
        </div>
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-gradient" style="background:var(--ga-gradient-gold)">
                <span class="stat-icon"><i class="bi bi-star"></i></span>
                <span class="stat-value"><?= (int)($user['points'] ?? 0) ?></span>
                <span class="stat-label">Pontos</span>
            </div>
        </div>
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-gradient" style="background:linear-gradient(135deg,#3B82F6,#1D4ED8)">
                <span class="stat-icon"><i class="bi bi-globe"></i></span>
                <span class="stat-value"><?= number_format($totalTreesGlobal) ?></span>
                <span class="stat-label">Total Global</span>
            </div>
        </div>
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card-gradient" style="background:linear-gradient(135deg,#8B5CF6,#6D28D9)">
                <span class="stat-icon"><i class="bi bi-people"></i></span>
                <span class="stat-value"><?= number_format($totalUsersGlobal) ?></span>
                <span class="stat-label">Contribuidores</span>
            </div>
        </div>
    </div>

    <!-- Climate Widget -->
    <div class="mb-4" data-aos="fade-up">
        <h5 class="section-title"><i class="bi bi-cloud-sun"></i> Clima & Qualidade do Ar</h5>
        <div id="clima-widget">
            <div class="row g-2">
                <div class="col-6 col-md-3"><div class="skeleton" style="height:100px"></div></div>
                <div class="col-6 col-md-3"><div class="skeleton" style="height:100px"></div></div>
                <div class="col-6 col-md-3"><div class="skeleton" style="height:100px"></div></div>
                <div class="col-6 col-md-3"><div class="skeleton" style="height:100px"></div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top Contributors -->
        <div class="col-md-6" data-aos="fade-up">
            <h5 class="section-title"><i class="bi bi-trophy"></i> Top Contribuidores</h5>
            <div class="glass-card p-0 overflow-hidden">
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($topContributors, 0, 5) as $i => $c): ?>
                    <div class="list-group-item d-flex align-items-center gap-3 bg-transparent">
                        <span class="fw-bold text-muted" style="width:24px"><?= $i + 1 ?>º</span>
                        <?php if (!empty($c['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($c['photo']) ?>" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder-sm"><?= strtoupper(mb_substr($c['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <div class="fw-bold small"><?= htmlspecialchars($c['name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($c['level_name'] ?? '') ?></small>
                        </div>
                        <span class="badge bg-success-subtle text-success"><?= (int)($c['points'] ?? 0) ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Trees -->
        <div class="col-md-6" data-aos="fade-up">
            <h5 class="section-title"><i class="bi bi-clock-history"></i> Minhas Últimas Árvores</h5>
            <?php if (empty($myTrees)): ?>
                <div class="glass-card p-4 text-center">
                    <i class="bi bi-tree text-success" style="font-size:2rem"></i>
                    <p class="text-muted mt-2 mb-3">Nenhuma árvore cadastrada.</p>
                    <a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-success btn-sm">Cadastrar primeira</a>
                </div>
            <?php else: ?>
                <div class="row g-2">
                    <?php foreach (array_slice($myTrees, 0, 4) as $t): ?>
                    <div class="col-6">
                        <div class="tree-card">
                            <?php if (!empty($t['photo'])): ?>
                                <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="">
                            <?php endif; ?>
                            <div class="card-body py-2 px-2">
                                <div class="species-name small"><?= htmlspecialchars($t['species_name'] ?? '') ?></div>
                                <span class="badge badge-status badge-saudavel" style="font-size:0.65rem"><?= htmlspecialchars($t['status_name'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= BASE_URL ?>minhas-arvores" class="btn btn-outline-success btn-sm mt-2 w-100">Ver todas</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$extraScripts = [BASE_URL . 'assets/js/clima.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchClimaWidget('clima-widget', window.BASE_URL);
});
</script>
