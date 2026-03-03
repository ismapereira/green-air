<?php
$pageTitle = 'Nova senha';
$token = $token ?? '';
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="auth-page">
    <div class="auth-card">
        <h1>Nova senha</h1>
        <form method="post" action="<?= BASE_URL ?>redefinir-senha" class="auth-form">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <label>
                <span>Nova senha (mín. 6 caracteres)</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Confirmar senha</span>
                <input type="password" name="password_confirm" required>
            </label>
            <button type="submit" class="btn btn-primary btn-block">Alterar senha</button>
        </form>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
