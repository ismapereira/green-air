<?php
$pageTitle = 'Ranking';
$user = $user ?? [];
$weekly = $weekly ?? [];
$monthly = $monthly ?? [];
$allTime = $allTime ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4">
    <h4 class="fw-bold mb-4 text-center"><i class="bi bi-trophy text-success me-2"></i>Ranking de Contribuidores</h4>

    <!-- Tabs -->
    <ul class="nav nav-pills justify-content-center mb-4" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tab-all">Geral</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-monthly">Este Mês</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-weekly">Semana</a></li>
    </ul>

    <div class="tab-content">
        <!-- All Time -->
        <div class="tab-pane fade show active" id="tab-all">
            <!-- Podium -->
            <?php if (count($allTime) >= 3): ?>
            <div class="podium mb-4" data-aos="fade-up">
                <!-- 2nd -->
                <div class="podium-item silver text-center">
                    <?php if (!empty($allTime[1]['photo'])): ?>
                        <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($allTime[1]['photo']) ?>" class="podium-avatar">
                    <?php else: ?>
                        <div class="podium-avatar d-inline-flex align-items-center justify-content-center" style="background:var(--ga-bg-card);font-weight:700;font-size:1.2rem"><?= strtoupper(mb_substr($allTime[1]['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <div class="small fw-bold"><?= htmlspecialchars(explode(' ', $allTime[1]['name'])[0]) ?></div>
                    <div class="podium-bar">🥈</div>
                </div>
                <!-- 1st -->
                <div class="podium-item gold text-center">
                    <?php if (!empty($allTime[0]['photo'])): ?>
                        <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($allTime[0]['photo']) ?>" class="podium-avatar">
                    <?php else: ?>
                        <div class="podium-avatar d-inline-flex align-items-center justify-content-center" style="background:var(--ga-bg-card);font-weight:700;font-size:1.5rem;width:72px;height:72px"><?= strtoupper(mb_substr($allTime[0]['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <div class="small fw-bold"><?= htmlspecialchars(explode(' ', $allTime[0]['name'])[0]) ?></div>
                    <div class="podium-bar">🥇</div>
                </div>
                <!-- 3rd -->
                <div class="podium-item bronze text-center">
                    <?php if (!empty($allTime[2]['photo'])): ?>
                        <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($allTime[2]['photo']) ?>" class="podium-avatar">
                    <?php else: ?>
                        <div class="podium-avatar d-inline-flex align-items-center justify-content-center" style="background:var(--ga-bg-card);font-weight:700;font-size:1.2rem"><?= strtoupper(mb_substr($allTime[2]['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <div class="small fw-bold"><?= htmlspecialchars(explode(' ', $allTime[2]['name'])[0]) ?></div>
                    <div class="podium-bar">🥉</div>
                </div>
            </div>
            <?php endif; ?>

            <!-- List -->
            <div class="glass-card overflow-hidden">
                <div class="list-group list-group-flush">
                    <?php foreach ($allTime as $i => $r): ?>
                    <div class="list-group-item d-flex align-items-center gap-3 bg-transparent <?= ($r['id'] ?? 0) == ($user['id'] ?? 0) ? 'bg-success bg-opacity-10' : '' ?>">
                        <span class="fw-bold text-muted" style="width:28px"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder-sm"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <div class="fw-bold small"><?= htmlspecialchars($r['name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($r['level_name'] ?? '') ?></small>
                        </div>
                        <span class="badge bg-success-subtle text-success fw-bold"><?= (int)($r['points'] ?? 0) ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Monthly -->
        <div class="tab-pane fade" id="tab-monthly">
            <?php if (empty($monthly)): ?>
                <p class="text-muted text-center">Sem dados este mês.</p>
            <?php else: ?>
            <div class="glass-card overflow-hidden">
                <div class="list-group list-group-flush">
                    <?php foreach ($monthly as $i => $r): ?>
                    <div class="list-group-item d-flex align-items-center gap-3 bg-transparent">
                        <span class="fw-bold text-muted" style="width:28px"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder-sm"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1"><div class="fw-bold small"><?= htmlspecialchars($r['name']) ?></div></div>
                        <span class="badge bg-success-subtle text-success"><?= (int)($r['total'] ?? 0) ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Weekly -->
        <div class="tab-pane fade" id="tab-weekly">
            <?php if (empty($weekly)): ?>
                <p class="text-muted text-center">Sem dados esta semana.</p>
            <?php else: ?>
            <div class="glass-card overflow-hidden">
                <div class="list-group list-group-flush">
                    <?php foreach ($weekly as $i => $r): ?>
                    <div class="list-group-item d-flex align-items-center gap-3 bg-transparent">
                        <span class="fw-bold text-muted" style="width:28px"><?= $i + 1 ?>º</span>
                        <?php if (!empty($r['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($r['photo']) ?>" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder-sm"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1"><div class="fw-bold small"><?= htmlspecialchars($r['name']) ?></div></div>
                        <span class="badge bg-success-subtle text-success"><?= (int)($r['total'] ?? 0) ?> pts</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
