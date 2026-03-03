<?php
$pageTitle = 'Cadastro';
$error = $error ?? null;
$old = $old ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="auth-page">
    <div class="auth-card auth-card-wide">
        <h1>Criar conta</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>registro" class="auth-form" enctype="multipart/form-data">
            <label>
                <span>Nome</span>
                <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            </label>
            <label>
                <span>E-mail</span>
                <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </label>
            <label>
                <span>Senha (mín. 6 caracteres)</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Confirmar senha</span>
                <input type="password" name="password_confirm" required>
            </label>
            <label>
                <span>Foto (opcional)</span>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            </label>
            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <p class="auth-footer">Já tem conta? <a href="<?= BASE_URL ?>login">Entrar</a></p>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
