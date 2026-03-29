<?php
$pageTitle = 'Cadastre-se';
$error = $error ?? null;
$old = $old ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se | Green Air</title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #059669 0%, #047857 30%, #064E3B 100%);
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
        }
        .auth-wrapper { width: 100%; max-width: 480px; }
        .auth-brand { text-align: center; margin-bottom: 1.25rem; }
        .auth-brand .icon { font-size: 2.5rem; display: block; margin-bottom: 0.4rem; }
        .auth-brand h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; }
        .auth-brand p { color: rgba(255,255,255,0.7); font-size: 0.88rem; margin: 0.25rem 0 0; }
        .auth-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .auth-card label {
            color: rgba(255,255,255,0.85);
            font-weight: 600;
            font-size: 0.82rem;
            margin-bottom: 0.35rem;
            display: block;
        }
        .input-group-icon { position: relative; }
        .input-group-icon i {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.45); font-size: 0.95rem; z-index: 2; pointer-events: none;
        }
        .input-group-icon input, .auth-card select, .auth-card input[type="file"] {
            width: 100%;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 10px;
            padding: 0.65rem 0.75rem 0.65rem 2.4rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            outline: none;
        }
        .auth-card select, .auth-card input[type="file"] { padding-left: 0.75rem; }
        .auth-card select option { color: #333; background: #fff; }
        .input-group-icon input::placeholder { color: rgba(255,255,255,0.4); }
        .input-group-icon input:focus, .auth-card select:focus, .auth-card input[type="file"]:focus {
            background: rgba(255,255,255,0.15);
            border-color: #F59E0B;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
        }
        .btn-auth {
            width: 100%; padding: 0.7rem; background: #059669; color: #fff;
            border: none; border-radius: 10px; font-weight: 700; font-size: 0.95rem;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-auth:hover { background: #047857; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .auth-link { color: rgba(255,255,255,0.65); text-decoration: none; font-size: 0.85rem; }
        .auth-link-accent { color: #F59E0B; font-weight: 700; text-decoration: none; }
        .auth-link-accent:hover { color: #FBBF24; }
        .auth-divider { border-color: rgba(255,255,255,0.12); margin: 1.25rem 0; }
        .auth-footer { text-align: center; margin-top: 1rem; }
        .alert { border-radius: 10px; border: none; font-size: 0.88rem; }
        a.auth-back {
            display: inline-flex; align-items: center; gap: 0.4rem;
            color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;
            margin-bottom: 0.75rem; transition: color 0.2s;
        }
        a.auth-back:hover { color: #fff; }
        .form-check-input { background-color: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); }
        .form-check-input:checked { background-color: #059669; border-color: #059669; }
        .form-check-label { color: rgba(255,255,255,0.7); font-size: 0.82rem; }
        .form-check-label a { color: #F59E0B; text-decoration: none; }
        .form-check-label a:hover { color: #FBBF24; text-decoration: underline; }

        .modal-content { border-radius: 16px !important; }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <a href="<?= BASE_URL ?>" class="auth-back"><i class="bi bi-arrow-left"></i> Voltar ao início</a>

    <div class="auth-brand">
        <i class="bi bi-tree-fill icon" style="color:#10B981"></i>
        <h1>Green Air</h1>
        <p>Crie sua conta e comece a contribuir</p>
    </div>

    <div class="auth-card">
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 mb-3"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>registro" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">

            <div class="mb-3">
                <label>Nome completo</label>
                <div class="input-group-icon">
                    <i class="bi bi-person"></i>
                    <input type="text" name="name" placeholder="Seu nome completo" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label>E-mail</label>
                <div class="input-group-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="seu@email.com" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label>Senha</label>
                    <div class="input-group-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" placeholder="Mín. 6 chars" required>
                    </div>
                </div>
                <div class="col-6">
                    <label>Confirmar</label>
                    <div class="input-group-icon">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password_confirm" placeholder="Repetir" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label><i class="bi bi-camera me-1"></i>Foto de perfil (opcional)</label>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">
                    Li e aceito os <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Termos de Uso</a>
                </label>
            </div>

            <button type="submit" class="btn-auth">
                <i class="bi bi-person-plus"></i> Criar Conta
            </button>
        </form>

        <hr class="auth-divider">

        <div class="auth-footer">
            <span class="auth-link">Já tem conta? </span>
            <a href="<?= BASE_URL ?>login" class="auth-link-accent">Entrar</a>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Termos de Uso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Ao utilizar o Green Air, você concorda com os seguintes termos:</p>
                <ul>
                    <li>Suas contribuições (fotos, localização das árvores) serão públicas.</li>
                    <li>Você se compromete a cadastrar informações verídicas.</li>
                    <li>Não armazenamos sua localização pessoal, apenas a das árvores cadastradas.</li>
                    <li>Uso indevido pode resultar em suspensão da conta.</li>
                    <li>As fotos enviadas devem ser de sua autoria.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendi</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
