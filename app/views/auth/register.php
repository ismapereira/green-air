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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="ga-auth-page">
    <div class="ga-auth-card" style="max-width:460px">
        <div class="text-center mb-3"><span style="font-size:2.5rem">🌱</span></div>
        <h1>Criar Conta</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>registro" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label class="form-label">Nome completo</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Seu nome" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Senha</label>
                    <div class="position-relative">
                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Mín. 6 chars" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label">Confirmar</label>
                    <div class="position-relative">
                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirm" class="form-control" placeholder="Repetir" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto (opcional)</label>
                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms" style="color:rgba(255,255,255,0.7);font-size:0.85rem">
                    Li e aceito os <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="color:var(--ga-accent)">Termos de Uso</a>
                </label>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold mb-3">
                <i class="bi bi-person-plus me-2"></i>Criar Conta
            </button>
        </form>
        <div class="text-center">
            <span style="color:rgba(255,255,255,0.6)">Já tem conta?</span>
            <a href="<?= BASE_URL ?>login" class="fw-bold" style="color:var(--ga-accent)">Entrar</a>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
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
