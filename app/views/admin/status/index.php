<?php
$pageTitle = 'Status';
$user = $user ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Status de Preservação</h4>
<div class="card mb-3">
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>admin/status" class="d-flex gap-2 flex-wrap">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <input type="text" name="name" class="form-control form-control-sm" placeholder="Nome" required style="max-width:200px">
            <input type="text" name="description" class="form-control form-control-sm" placeholder="Descrição" style="max-width:200px">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Adicionar</button>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Ações</th></tr></thead>
        <tbody>
        <?php foreach ($statuses as $st): ?>
            <tr>
                <td><?= (int)$st['id'] ?></td>
                <td><?= htmlspecialchars($st['name']) ?></td>
                <td class="text-muted small"><?= htmlspecialchars($st['description']??'') ?></td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>admin/status/editar/<?= (int)$st['id'] ?>" class="d-inline-flex gap-1 align-items-center">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($st['name']) ?>" class="form-control form-control-sm" style="width:120px" required>
                        <input type="text" name="description" value="<?= htmlspecialchars($st['description']??'') ?>" class="form-control form-control-sm" style="width:120px">
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                    </form>
                    <form method="post" action="<?= BASE_URL ?>admin/status/excluir/<?= (int)$st['id'] ?>" class="d-inline" onsubmit="return confirm('Excluir?');">
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
