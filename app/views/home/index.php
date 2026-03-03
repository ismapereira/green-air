<?php
$pageTitle = 'Início';
require ROOT_PATH . '/app/views/layout/header.php';
?>
<section class="hero">
    <div class="container hero-inner">
        <h1>Mapeie árvores da sua cidade</h1>
        <p class="hero-sub">Contribua com fotos e localização. Acompanhe o mapa colaborativo e suba de nível.</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>registro" class="btn btn-primary">Começar agora</a>
            <a href="<?= BASE_URL ?>mapa" class="btn btn-secondary">Ver mapa</a>
        </div>
    </div>
</section>
<section class="features">
    <div class="container">
        <h2>Como funciona</h2>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">📍</span>
                <h3>Cadastre</h3>
                <p>Envie uma foto e a localização é capturada automaticamente pelo seu celular ou navegador.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🗺️</span>
                <h3>Mapa interativo</h3>
                <p>Visualize todas as árvores no mapa. Filtre por espécie, status e bairro.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🏆</span>
                <h3>Níveis e ranking</h3>
                <p>Ganhe pontos por contribuição. Bronze, Prata e Ouro desbloqueiam novas ações.</p>
            </div>
        </div>
    </div>
</section>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
