<?php
$pageTitle = 'Entrar';
$error = $error ?? null;
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="auth-page">
    <div class="auth-card">
        <h1>Entrar</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['register_success'])): unset($_SESSION['register_success']); ?>
            <div class="alert alert-success">Cadastro realizado! Faça login.</div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>login" class="auth-form">
            <label>
                <span>E-mail</span>
                <input type="email" name="email" required autofocus>
            </label>
            <label>
                <span>Senha</span>
                <input type="password" name="password" required>
            </label>
            <a href="<?= BASE_URL ?>esqueci-senha" class="forgot-link">Esqueci a senha</a>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
        <p class="auth-footer">Não tem conta? <a href="<?= BASE_URL ?>registro">Cadastre-se</a></p>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
