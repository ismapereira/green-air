</main>

<!-- Bottom Navigation (Mobile) -->
<?php if ($currentUser ?? null): ?>
<nav class="ga-bottom-nav" id="bottom-nav">
    <a href="<?= BASE_URL ?>painel" class="bnav-item <?= $route === '/painel' ? 'active' : '' ?>">
        <i class="bi bi-grid"></i>
        <span>Painel</span>
    </a>
    <a href="<?= BASE_URL ?>mapa" class="bnav-item <?= $route === '/mapa' ? 'active' : '' ?>">
        <i class="bi bi-map"></i>
        <span>Mapa</span>
    </a>
    <a href="<?= BASE_URL ?>cadastrar-arvore" class="bnav-item bnav-add" title="Cadastrar Árvore">
        <i class="bi bi-plus-lg"></i>
    </a>
    <a href="<?= BASE_URL ?>ranking" class="bnav-item <?= $route === '/ranking' ? 'active' : '' ?>">
        <i class="bi bi-trophy"></i>
        <span>Ranking</span>
    </a>
    <a href="<?= BASE_URL ?>perfil" class="bnav-item <?= $route === '/perfil' ? 'active' : '' ?>">
        <i class="bi bi-person"></i>
        <span>Perfil</span>
    </a>
</nav>
<?php endif; ?>

<!-- Footer -->
<footer class="ga-footer">
    <div class="container">
        <div class="row g-4 text-center text-md-start mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start mb-2">
                    <i class="bi bi-tree-fill fs-4" style="color:var(--ga-primary)"></i>
                    <span class="fw-bold fs-5 text-gradient">Green Air</span>
                </div>
                <p class="mb-0 small">Mapeamento colaborativo de árvores urbanas para uma cidade mais verde e sustentável.</p>
            </div>
            <div class="col-6 col-md-2">
                <h6 class="fw-bold mb-2" style="color:var(--ga-primary)">Navegação</h6>
                <a href="<?= BASE_URL ?>" class="d-block small mb-1 text-muted">Início</a>
                <a href="<?= BASE_URL ?>mapa" class="d-block small mb-1 text-muted">Mapa</a>
                <a href="<?= BASE_URL ?>ranking" class="d-block small mb-1 text-muted">Ranking</a>
            </div>
            <div class="col-6 col-md-2">
                <h6 class="fw-bold mb-2" style="color:var(--ga-primary)">Conta</h6>
                <?php if ($currentUser ?? null): ?>
                    <a href="<?= BASE_URL ?>painel" class="d-block small mb-1 text-muted">Painel</a>
                    <a href="<?= BASE_URL ?>perfil" class="d-block small mb-1 text-muted">Perfil</a>
                    <a href="<?= BASE_URL ?>minhas-arvores" class="d-block small mb-1 text-muted">Minhas Árvores</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="d-block small mb-1 text-muted">Entrar</a>
                    <a href="<?= BASE_URL ?>registro" class="d-block small mb-1 text-muted">Cadastre-se</a>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold mb-2" style="color:var(--ga-primary)">Sobre o Projeto</h6>
                <p class="small mb-2 text-muted">Contribua para o mapeamento das árvores da sua região. Cada registro conta para um futuro mais sustentável!</p>
                <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                    <span class="badge bg-success-subtle text-success"><i class="bi bi-tree me-1"></i>Open Source</span>
                    <span class="badge bg-success-subtle text-success"><i class="bi bi-shield-check me-1"></i>Seguro</span>
                </div>
            </div>
        </div>
        <hr style="border-color: var(--ga-border); margin: 0 0 1rem 0;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small text-muted">&copy; <?= date('Y') ?> Green Air. Feito com <i class="bi bi-heart-fill" style="color:var(--ga-primary)"></i> para o meio ambiente.</p>
            <div class="d-flex gap-3 small">
                <a href="<?= BASE_URL ?>termos" class="text-muted">Termos</a>
                <a href="<?= BASE_URL ?>privacidade" class="text-muted">Privacidade</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script>window.CSRF_TOKEN = <?= json_encode($csrfToken ?? '') ?>;</script>

<!-- Green Air JS -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>

<?php if (!empty($extraScripts)):
    foreach ($extraScripts as $script): ?>
        <script src="<?= $script ?>"></script>
<?php endforeach; endif; ?>

</body>
</html>
