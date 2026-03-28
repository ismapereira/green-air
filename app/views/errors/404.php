<?php
http_response_code(404);
$pageTitle = 'Página não encontrada';
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-5 text-center">
    <div style="font-size:5rem;margin-bottom:1rem">🌲</div>
    <h1 class="fw-bold">404</h1>
    <p class="text-muted mb-4">Oops! Parece que essa árvore não foi encontrada no nosso mapa.</p>
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <a href="<?= BASE_URL ?>" class="btn btn-success"><i class="bi bi-house me-2"></i>Início</a>
        <a href="<?= BASE_URL ?>mapa" class="btn btn-outline-success"><i class="bi bi-map me-2"></i>Mapa</a>
        <a href="<?= BASE_URL ?>ranking" class="btn btn-outline-secondary"><i class="bi bi-trophy me-2"></i>Ranking</a>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
