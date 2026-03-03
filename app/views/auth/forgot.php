<?php
$pageTitle = 'Recuperar senha';
$message = $message ?? null;
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="auth-page">
    <div class="auth-card">
        <h1>Recuperar senha</h1>
        <?php if ($message): ?>
            <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($message['text']) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>esqueci-senha" class="auth-form">
            <label>
                <span>E-mail</span>
                <input type="email" name="email" required>
            </label>
            <button type="submit" class="btn btn-primary btn-block">Enviar link</button>
        </form>
        <p class="auth-footer"><a href="<?= BASE_URL ?>login">Voltar ao login</a></p>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
