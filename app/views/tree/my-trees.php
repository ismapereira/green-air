<?php
$pageTitle = 'Minhas Árvores';
$user = $user ?? [];
$trees = $trees ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="bi bi-tree text-success me-2"></i>Minhas Árvores</h4>
        <a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Nova</a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($trees)): ?>
        <div class="text-center py-5">
            <i class="bi bi-tree" style="font-size:3rem;color:var(--ga-primary)"></i>
            <p class="text-muted mt-3">Nenhuma árvore cadastrada ainda.</p>
            <a href="<?= BASE_URL ?>cadastrar-arvore" class="btn btn-success">Cadastrar primeira árvore</a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($trees as $t): ?>
            <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                <div class="tree-card h-100">
                    <?php if (!empty($t['photo'])): ?>
                        <a href="<?= BASE_URL ?>arvore/<?= (int)$t['id'] ?>">
                            <img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" alt="">
                        </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="species-name"><?= htmlspecialchars($t['species_name'] ?? '') ?></div>
                        <span class="badge badge-status badge-saudavel mt-1"><?= htmlspecialchars($t['status_name'] ?? '') ?></span>
                        <?php if (!empty($t['address'])): ?>
                            <div class="text-muted small mt-1"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars(mb_substr($t['address'], 0, 35)) ?></div>
                        <?php endif; ?>
                        <div class="d-flex gap-1 mt-2">
                            <a href="<?= BASE_URL ?>arvore/editar/<?= (int)$t['id'] ?>" class="btn btn-outline-success btn-sm flex-grow-1"><i class="bi bi-pencil"></i></a>
                            <a href="<?= BASE_URL ?>arvore/<?= (int)$t['id'] ?>" class="btn btn-outline-secondary btn-sm flex-grow-1"><i class="bi bi-eye"></i></a>
                            <form method="post" action="<?= BASE_URL ?>arvore/excluir/<?= (int)$t['id'] ?>" onsubmit="return confirm('Excluir esta árvore?');" class="flex-grow-1">
                                <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
