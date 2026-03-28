<?php
$pageTitle = 'Árvores';
$user = $user ?? [];
$trees = $trees ?? [];
$species = $species ?? [];
$statuses = $statuses ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-tree me-2"></i>Árvores</h4>
<form method="get" class="d-flex gap-2 flex-wrap mb-3">
    <select name="species_id" class="form-select form-select-sm" style="width:auto">
        <option value="">Todas espécies</option>
        <?php foreach ($species as $s): ?><option value="<?= (int)$s['id'] ?>" <?= (isset($_GET['species_id']) && (int)$_GET['species_id'] === (int)$s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option><?php endforeach; ?>
    </select>
    <select name="status_id" class="form-select form-select-sm" style="width:auto">
        <option value="">Todos status</option>
        <?php foreach ($statuses as $st): ?><option value="<?= (int)$st['id'] ?>" <?= (isset($_GET['status_id']) && (int)$_GET['status_id'] === (int)$st['id']) ? 'selected' : '' ?>><?= htmlspecialchars($st['name']) ?></option><?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-funnel me-1"></i>Filtrar</button>
</form>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Foto</th><th>Espécie</th><th>Status</th><th>Endereço</th><th>Cadastrante</th><th>Ações</th></tr></thead>
        <tbody>
        <?php foreach ($trees as $t): ?>
            <tr>
                <td><?= (int)$t['id'] ?></td>
                <td><?php if (!empty($t['photo'])): ?><img src="<?= BASE_URL ?>uploads/trees/<?= htmlspecialchars($t['photo']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
                <td><?= htmlspecialchars($t['species_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($t['status_name'] ?? '') ?></td>
                <td class="small"><?= htmlspecialchars(mb_substr($t['address'] ?? '', 0, 30)) ?></td>
                <td class="small"><?= htmlspecialchars($t['user_name'] ?? '') ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/arvores/editar/<?= (int)$t['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="<?= BASE_URL ?>admin/arvores/excluir/<?= (int)$t['id'] ?>" class="d-inline" onsubmit="return confirm('Excluir?');">
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
