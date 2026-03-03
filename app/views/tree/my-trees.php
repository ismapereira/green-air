<?php
$pageTitle = 'Minhas árvores';
$user = $user ?? [];
$trees = $trees ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container">
    <h1>Minhas árvores</h1>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <p><a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-primary">Cadastrar nova árvore</a></p>
    <?php if (empty($trees)): ?>
        <p>Você ainda não cadastrou nenhuma árvore.</p>
    <?php else: ?>
        <ul class="tree-list">
            <?php foreach ($trees as $t): ?>
                <li class="tree-item">
                    <?php if (!empty($t['photo'])): ?>
                        <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="">
                    <?php endif; ?>
                    <div class="tree-info">
                        <strong><?= htmlspecialchars($t['species_name']) ?></strong>
                        <span class="badge"><?= htmlspecialchars($t['status_name']) ?></span>
                        <?php if (!empty($t['address'])): ?><br><small><?= htmlspecialchars($t['address']) ?></small><?php endif; ?>
                        <?php if (!empty($t['size'])): ?><br><small>Tamanho: <?= htmlspecialchars($t['size']) ?></small><?php endif; ?>
                    </div>
                    <div class="tree-actions">
                        <a href="<?= BASE_URL ?>arvore/editar/<?= (int)$t['id'] ?>" class="btn btn-small">Editar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
