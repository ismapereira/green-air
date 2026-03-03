<?php
http_response_code(404);
$pageTitle = 'Página não encontrada';
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container error-page">
    <h1>404</h1>
    <p>Página não encontrada.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary">Voltar ao início</a>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
