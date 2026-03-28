<?php
$pageTitle = 'Entrar';
$error = $error ?? ($_SESSION['login_error'] ?? null);
$registerSuccess = $_SESSION['register_success'] ?? false;
unset($_SESSION['login_error'], $_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Green Air</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="ga-auth-page">
    <div class="ga-auth-card">
        <div class="text-center mb-3">
            <span style="font-size:2.5rem">🌳</span>
        </div>
        <h1>Entrar</h1>

        <?php if ($registerSuccess): ?>
            <div class="alert alert-success py-2"><i class="bi bi-check-circle me-2"></i>Conta criada com sucesso! Faça login.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>login">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
        </form>
        <div class="text-center">
            <a href="<?= BASE_URL ?>esqueci-senha" style="color:rgba(255,255,255,0.7)">Esqueci minha senha</a>
        </div>
        <hr style="border-color:rgba(255,255,255,0.15)">
        <div class="text-center">
            <span style="color:rgba(255,255,255,0.6)">Não tem conta?</span>
            <a href="<?= BASE_URL ?>registro" class="fw-bold" style="color:var(--ga-accent)">Cadastre-se</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
