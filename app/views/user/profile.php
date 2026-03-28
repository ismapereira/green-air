<?php
$pageTitle = 'Perfil';
$user = $user ?? [];
$contributions = $contributions ?? [];
$levelProgress = $levelProgress ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4" style="max-width:700px">
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Profile Hero -->
    <div class="profile-card-hero mb-4" data-aos="fade-up">
        <?php if (!empty($user['photo'])): ?>
            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($user['photo']) ?>" class="profile-avatar">
        <?php else: ?>
            <div class="avatar-placeholder"><?= strtoupper(mb_substr($user['name'], 0, 1)) ?></div>
        <?php endif; ?>
        <h4 class="fw-bold mb-0"><?= htmlspecialchars($user['name']) ?></h4>
        <small style="opacity:0.8"><?= htmlspecialchars($user['email']) ?></small>
        <div class="mt-2">
            <span class="badge bg-white text-dark fw-bold"><?= htmlspecialchars($user['level_name'] ?? 'Bronze') ?></span>
            <span class="ms-1" style="opacity:0.8"><?= (int)($user['points'] ?? 0) ?> pontos</span>
        </div>
        <?php if (!empty($levelProgress['next'])): ?>
        <div class="level-progress">
            <div class="level-progress-fill" style="width:<?= $levelProgress['progress'] ?>%"></div>
        </div>
        <small style="opacity:0.7"><?= $levelProgress['points_to_next'] ?> pts para <?= htmlspecialchars($levelProgress['next']['name'] ?? '') ?></small>
        <?php endif; ?>
    </div>

    <!-- Edit Form -->
    <div class="glass-card p-3 mb-4" data-aos="fade-up">
        <h5 class="section-title"><i class="bi bi-pencil-square"></i> Editar Perfil</h5>
        <form method="post" action="<?= BASE_URL ?>perfil" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label class="form-label fw-bold">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nova foto</label>
                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Nova senha</label>
                    <input type="password" name="password" class="form-control" placeholder="Deixe vazio para manter">
                </div>
                <div class="col-6">
                    <label class="form-label">Confirmar</label>
                    <input type="password" name="password_confirm" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-2"></i>Salvar</button>
        </form>
    </div>

    <!-- Contributions -->
    <div data-aos="fade-up">
        <h5 class="section-title"><i class="bi bi-clock-history"></i> Últimas Contribuições</h5>
        <?php if (empty($contributions)): ?>
            <p class="text-muted small">Nenhuma contribuição ainda.</p>
        <?php else: ?>
        <div class="list-group list-group-flush glass-card overflow-hidden">
            <?php foreach ($contributions as $c): ?>
            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center small">
                <div>
                    <i class="bi bi-dot text-success"></i>
                    <?= htmlspecialchars($c['action']) ?>
                    <?php if ($c['points_awarded']): ?>
                        <span class="badge bg-success-subtle text-success ms-1">+<?= (int)$c['points_awarded'] ?></span>
                    <?php endif; ?>
                </div>
                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
