<?php
$pageTitle = 'Início';
$currentUser = $currentUser ?? null;
$treeModel = new Tree();
$userModel = new User();
$totalTrees = $treeModel->count();
$totalUsers = $userModel->count();
require ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Hero -->
<section class="ga-hero">
    <div class="container position-relative">
        <h1 data-aos="fade-up">Mapeie as árvores<br>da sua cidade</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">
            Contribua para um futuro mais verde. Cadastre árvores urbanas, acompanhe a qualidade do ar e faça parte da mudança.
        </p>
        <div data-aos="fade-up" data-aos-delay="200">
            <?php if ($currentUser): ?>
                <a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-light btn-lg me-2"><i class="bi bi-plus-circle me-2"></i>Cadastrar Árvore</a>
                <a href="<?= BASE_URL ?>mapa" class="btn btn-outline-light btn-lg"><i class="bi bi-map me-2"></i>Ver Mapa</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>registro" class="btn btn-light btn-lg me-2"><i class="bi bi-person-plus me-2"></i>Comece Agora</a>
                <a href="<?= BASE_URL ?>mapa" class="btn btn-outline-light btn-lg"><i class="bi bi-map me-2"></i>Explorar Mapa</a>
            <?php endif; ?>
        </div>
        <div class="hero-stats" data-aos="fade-up" data-aos-delay="300">
            <div class="hero-stat">
                <span class="stat-num" id="counter-trees"><?= number_format($totalTrees) ?></span>
                <span class="stat-label">Árvores</span>
            </div>
            <div class="hero-stat">
                <span class="stat-num" id="counter-users"><?= number_format($totalUsers) ?></span>
                <span class="stat-label">Contribuidores</span>
            </div>
            <div class="hero-stat">
                <span class="stat-num">🌍</span>
                <span class="stat-label">Colaborativo</span>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4" data-aos="fade-up"><i class="bi bi-signpost-split text-success me-2"></i>Como funciona</h2>
        <div class="row g-4">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-person-plus"></i></div>
                    <h3>1. Cadastre-se</h3>
                    <p class="text-muted small mb-0">Crie sua conta gratuita em segundos.</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-camera"></i></div>
                    <h3>2. Fotografe</h3>
                    <p class="text-muted small mb-0">Tire uma foto da árvore com GPS automático.</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-tree"></i></div>
                    <h3>3. Cadastre</h3>
                    <p class="text-muted small mb-0">Informe espécie, status e observações.</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="bi bi-trophy"></i></div>
                    <h3>4. Pontue</h3>
                    <p class="text-muted small mb-0">Ganhe pontos e suba no ranking!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5" style="background: var(--ga-bg-card);">
    <div class="container">
        <h2 class="text-center fw-bold mb-4" data-aos="fade-up"><i class="bi bi-stars text-success me-2"></i>Recursos</h2>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="d-flex gap-3 align-items-start">
                    <div class="feature-icon flex-shrink-0"><i class="bi bi-map"></i></div>
                    <div>
                        <h5 class="fw-bold">Mapa Interativo</h5>
                        <p class="text-muted small mb-0">Visualize todas as árvores cadastradas com filtros por espécie, status e localização.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex gap-3 align-items-start">
                    <div class="feature-icon flex-shrink-0"><i class="bi bi-cloud-sun"></i></div>
                    <div>
                        <h5 class="fw-bold">Dados Climáticos</h5>
                        <p class="text-muted small mb-0">Acompanhe temperatura, umidade, qualidade do ar e poluentes em tempo real.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="d-flex gap-3 align-items-start">
                    <div class="feature-icon flex-shrink-0"><i class="bi bi-bar-chart-line"></i></div>
                    <div>
                        <h5 class="fw-bold">Gamificação</h5>
                        <p class="text-muted small mb-0">Sistema de pontos, níveis e ranking para estimular contribuições.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<?php if (!$currentUser): ?>
<section class="py-5" data-aos="fade-up">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Faça parte dessa mudança 🌿</h2>
        <p class="text-muted mb-4">Junte-se a <?= number_format($totalUsers) ?> contribuidores que estão mapeando a arborização urbana.</p>
        <a href="<?= BASE_URL ?>registro" class="btn btn-success btn-lg"><i class="bi bi-person-plus me-2"></i>Criar Conta Gratuita</a>
    </div>
</section>
<?php endif; ?>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
