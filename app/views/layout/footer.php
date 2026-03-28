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
<footer class="ga-footer d-none d-md-block">
    <div class="container">
        <div class="row g-3 text-center text-md-start">
            <div class="col-md-4">
                <strong class="text-gradient">🌳 Green Air</strong>
                <p class="mb-0 mt-1">Mapeamento colaborativo de árvores urbanas para uma cidade mais verde.</p>
            </div>
            <div class="col-md-4">
                <strong>Links</strong>
                <div class="mt-1">
                    <a href="<?= BASE_URL ?>" class="d-block">Início</a>
                    <a href="<?= BASE_URL ?>mapa" class="d-block">Mapa</a>
                    <a href="<?= BASE_URL ?>ranking" class="d-block">Ranking</a>
                </div>
            </div>
            <div class="col-md-4">
                <strong>Sobre</strong>
                <p class="mb-0 mt-1">Contribua para o mapeamento das árvores da sua região. Cada registro conta!</p>
            </div>
        </div>
        <hr class="my-3" style="border-color: var(--ga-border);">
        <p class="mb-0">&copy; <?= date('Y') ?> Green Air. Feito com 💚 para o meio ambiente.</p>
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
