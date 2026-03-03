<?php
$pageTitle = 'Ranking';
$user = $user ?? [];
$weekly = $weekly ?? [];
$monthly = $monthly ?? [];
$allTime = $allTime ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container ranking-page">
    <h1>Ranking de contribuidores</h1>
    <div class="ranking-tabs">
        <div class="ranking-panel" data-tab="weekly">
            <h2>Esta semana</h2>
            <ol class="ranking-list">
                <?php foreach ($weekly as $i => $r): ?>
                    <li>
                        <span class="rank"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" alt="" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <span class="name"><?= htmlspecialchars($r['name']) ?></span>
                        <span class="points"><?= (int)($r['total'] ?? 0) ?> pts</span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class="ranking-panel" data-tab="monthly">
            <h2>Este mês</h2>
            <ol class="ranking-list">
                <?php foreach ($monthly as $i => $r): ?>
                    <li>
                        <span class="rank"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" alt="" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <span class="name"><?= htmlspecialchars($r['name']) ?></span>
                        <span class="points"><?= (int)($r['total'] ?? 0) ?> pts</span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class="ranking-panel active" data-tab="all">
            <h2>Geral (pontos totais)</h2>
            <ol class="ranking-list">
                <?php foreach ($allTime as $i => $r): ?>
                    <li class="<?= ($r['id'] ?? 0) == ($user['id'] ?? 0) ? 'me' : '' ?>">
                        <span class="rank"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" alt="" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <span class="name"><?= htmlspecialchars($r['name']) ?></span>
                        <span class="level-badge"><?= htmlspecialchars($r['level_name'] ?? '') ?></span>
                        <span class="points"><?= (int)($r['points'] ?? 0) ?> pts</span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
