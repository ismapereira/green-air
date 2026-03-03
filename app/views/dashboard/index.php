<?php
$pageTitle = 'Painel';
$user = $user ?? [];
$myTrees = $myTrees ?? [];
$topContributors = $topContributors ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container dashboard-page">
    <h1>Olá, <?= htmlspecialchars($user['name']) ?></h1>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <div class="dashboard-cards">
        <div class="stat-card">
            <span class="stat-value"><?= count($myTrees) ?></span>
            <span class="stat-label">Minhas árvores</span>
        </div>
        <div class="stat-card">
            <span class="stat-value"><?= (int)($user['points'] ?? 0) ?></span>
            <span class="stat-label">Pontos</span>
        </div>
        <div class="stat-card level-card">
            <span class="stat-value"><?= htmlspecialchars($user['level_name'] ?? 'Bronze') ?></span>
            <span class="stat-label">Nível</span>
        </div>
    </div>
    <section class="dashboard-section">
        <h2>Clima e qualidade do ar</h2>
        <div id="clima-widget" class="clima-widget">
            <p class="loading">Carregando...</p>
        </div>
    </section>
    <section class="dashboard-section">
        <h2>Top contribuidores</h2>
        <ul class="top-list">
            <?php foreach ($topContributors as $i => $c): ?>
                <li>
                    <span class="rank"><?= $i + 1 ?></span>
                    <?php if (!empty($c['photo'])): ?>
                        <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($c['photo']) ?>" alt="" class="avatar-sm">
                    <?php else: ?>
                        <span class="avatar-placeholder"><?= strtoupper(mb_substr($c['name'], 0, 1)) ?></span>
                    <?php endif; ?>
                    <span class="name"><?= htmlspecialchars($c['name']) ?></span>
                    <span class="points"><?= (int)$c['points'] ?> pts</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="dashboard-section">
        <h2>Minhas árvores recentes</h2>
        <?php if (empty($myTrees)): ?>
            <p>Você ainda não cadastrou árvores. <a href="<?= BASE_URL ?>cadastrar-arvore">Cadastrar primeira árvore</a></p>
        <?php else: ?>
            <ul class="tree-list-mini">
                <?php foreach (array_slice($myTrees, 0, 5) as $t): ?>
                    <li>
                        <?php if (!empty($t['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="">
                        <?php endif; ?>
                        <div>
                            <strong><?= htmlspecialchars($t['species_name']) ?></strong> — <?= htmlspecialchars($t['status_name']) ?>
                            <?php if (!empty($t['address'])): ?> · <?= htmlspecialchars(mb_substr($t['address'], 0, 40)) ?>...<?php endif; ?>
                        </div>
                        <a href="<?= BASE_URL ?>arvore/editar/<?= (int)$t['id'] ?>">Editar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>minhas-arvores" class="btn btn-secondary">Ver todas</a>
        <?php endif; ?>
    </section>
</div>
<?php
$extraScripts = [BASE_URL . 'assets/js/clima.js'];
require ROOT_PATH . '/app/views/layout/footer.php';
?>
