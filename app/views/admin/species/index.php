<?php
$pageTitle = 'Espécies';
$user = $user ?? [];
$species = $species ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-flower1 me-2"></i>Espécies</h4>
<div class="card mb-3">
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>admin/especies" class="d-flex gap-2 flex-wrap">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <input type="text" name="name" class="form-control form-control-sm" placeholder="Nome" required style="max-width:200px">
            <input type="text" name="scientific_name" class="form-control form-control-sm" placeholder="Nome científico" style="max-width:200px">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Adicionar</button>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Nome</th><th>Nome Científico</th><th>Ações</th></tr></thead>
        <tbody>
        <?php foreach ($species as $s): ?>
            <tr>
                <td><?= (int)$s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td class="fst-italic text-muted"><?= htmlspecialchars($s['scientific_name']??'') ?></td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>admin/especies/editar/<?= (int)$s['id'] ?>" class="d-inline-flex gap-1 align-items-center">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" class="form-control form-control-sm" style="width:120px" required>
                        <input type="text" name="scientific_name" value="<?= htmlspecialchars($s['scientific_name']??'') ?>" class="form-control form-control-sm" style="width:120px">
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                    </form>
                    <form method="post" action="<?= BASE_URL ?>admin/especies/excluir/<?= (int)$s['id'] ?>" class="d-inline" onsubmit="return confirm('Excluir?');">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
