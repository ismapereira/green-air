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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #059669 0%, #047857 30%, #064E3B 100%);
            padding: 1rem; -webkit-font-smoothing: antialiased;
        }
        .auth-wrapper { width: 100%; max-width: 420px; }
        .auth-brand { text-align: center; margin-bottom: 1.5rem; }
        .auth-brand .icon { font-size: 2.8rem; display: block; margin-bottom: 0.5rem; }
        .auth-brand h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; }
        .auth-brand p { color: rgba(255,255,255,0.7); font-size: 0.88rem; margin: 0.25rem 0 0; }
        .auth-card {
            background: rgba(255,255,255,0.12); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.18); border-radius: 20px; padding: 2rem; box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .auth-card label { color: rgba(255,255,255,0.85); font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; }
        .input-group-icon { position: relative; }
        .input-group-icon i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.45); font-size: 1rem; z-index: 2; pointer-events: none; }
        .input-group-icon input {
            width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff;
            border-radius: 10px; padding: 0.7rem 0.75rem 0.7rem 2.5rem; font-size: 0.95rem; transition: all 0.2s; outline: none;
        }
        .input-group-icon input::placeholder { color: rgba(255,255,255,0.4); }
        .input-group-icon input:focus { background: rgba(255,255,255,0.15); border-color: #F59E0B; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
        .btn-auth {
            width: 100%; padding: 0.75rem; background: #059669; color: #fff; border: none; border-radius: 10px;
            font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-auth:hover { background: #047857; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .alert { border-radius: 10px; border: none; font-size: 0.9rem; }
        a.auth-back {
            display: inline-flex; align-items: center; gap: 0.4rem; color: rgba(255,255,255,0.6);
            font-size: 0.85rem; text-decoration: none; margin-bottom: 1rem; transition: color 0.2s;
        }
        a.auth-back:hover { color: #fff; }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <a href="<?= BASE_URL ?>login" class="auth-back"><i class="bi bi-arrow-left"></i> Voltar ao login</a>

    <div class="auth-brand">
        <i class="bi bi-key-fill icon" style="color:#F59E0B"></i>
        <h1>Recuperar Senha</h1>
        <p>Informe seu e-mail para receber o link</p>
    </div>

    <div class="auth-card">
        <?php if ($message): ?>
            <?php
                $alertType = 'danger';
                if ($message['type'] === 'success') $alertType = 'success';
                if ($message['type'] === 'info') $alertType = 'info';
            ?>
            <div class="alert alert-<?= $alertType ?> py-2 mb-3">
                <?php if ($message['type'] === 'info' && !empty($_SESSION['reset_link'])): ?>
                    <div class="mb-2"><i class="bi bi-info-circle me-1"></i> Ambiente de desenvolvimento — e-mail não enviado.</div>
                    <a href="<?= htmlspecialchars($_SESSION['reset_link']) ?>" class="btn btn-sm btn-success w-100">
                        <i class="bi bi-arrow-right-circle me-1"></i> Redefinir Senha Agora
                    </a>
                    <?php unset($_SESSION['reset_link']); ?>
                <?php else: ?>
                    <?= htmlspecialchars($message['text']) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>esqueci-senha">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label>E-mail</label>
                <div class="input-group-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="seu@email.com" required autofocus>
                </div>
            </div>
            <button type="submit" class="btn-auth"><i class="bi bi-send"></i> Enviar Link</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
