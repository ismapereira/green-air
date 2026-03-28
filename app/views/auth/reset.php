<?php
$pageTitle = 'Nova Senha';
$token = $token ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha | Green Air</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="ga-auth-page">
    <div class="ga-auth-card">
        <div class="text-center mb-3"><span style="font-size:2.5rem">🔐</span></div>
        <h1 style="font-size:1.5rem">Nova Senha</h1>
        <form method="post" action="<?= BASE_URL ?>redefinir-senha">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="mb-3">
                <label class="form-label">Nova senha (mín. 6 caracteres)</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Alterar Senha</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
