<?php
$pageTitle = 'Recuperar senha';
$message = $message ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha | Green Air</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="ga-auth-page">
    <div class="ga-auth-card">
        <div class="text-center mb-3"><span style="font-size:2.5rem">🔑</span></div>
        <h1 style="font-size:1.5rem">Recuperar Senha</h1>
        <?php if ($message): ?>
            <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?> py-2 small"><?= htmlspecialchars($message['text']) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>esqueci-senha">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Enviar Link</button>
        </form>
        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>login" style="color:rgba(255,255,255,0.7)"><i class="bi bi-arrow-left me-1"></i>Voltar ao login</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
