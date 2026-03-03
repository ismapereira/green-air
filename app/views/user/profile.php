<?php
$pageTitle = 'Perfil';
$user = $user ?? [];
$contributions = $contributions ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container profile-page">
    <h1>Meu perfil</h1>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <div class="profile-grid">
        <div class="profile-card">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($user['photo']) ?>" alt="" class="profile-photo">
            <?php else: ?>
                <div class="profile-photo-placeholder"><?= strtoupper(mb_substr($user['name'], 0, 1)) ?></div>
            <?php endif; ?>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <p><strong><?= htmlspecialchars($user['level_name'] ?? 'Bronze') ?></strong> &middot; <?= (int)($user['points'] ?? 0) ?> pontos</p>
        </div>
        <div class="profile-form-card">
            <h2>Editar perfil</h2>
            <form method="post" action="<?= BASE_URL ?>perfil" enctype="multipart/form-data">
                <label>
                    <span>Nome</span>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </label>
                <label>
                    <span>Nova foto (opcional)</span>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                </label>
                <label>
                    <span>Nova senha (deixe em branco para não alterar)</span>
                    <input type="password" name="password">
                </label>
                <label>
                    <span>Confirmar nova senha</span>
                    <input type="password" name="password_confirm">
                </label>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
    <section class="contributions-section">
        <h2>Últimas contribuições</h2>
        <ul class="contributions-list">
            <?php foreach ($contributions as $c): ?>
                <li>
                    <span class="action"><?= htmlspecialchars($c['action']) ?></span>
                    <?php if ($c['points_awarded']): ?><span class="points">+<?= (int)$c['points_awarded'] ?> pts</span><?php endif; ?>
                    <span class="date"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
