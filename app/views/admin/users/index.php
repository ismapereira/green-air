<?php
$pageTitle = 'Usuários';
$user = $user ?? [];
$users = $users ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Usuários</h4>
    <a href="<?= BASE_URL ?>admin/usuarios/novo" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Novo</a>
</div>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead><tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Role</th><th>Nível</th><th>Pontos</th><th>Ações</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td class="small"><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge bg-<?= $u['role']==='admin'?'danger':($u['role']==='moderator'?'warning':'secondary') ?>"><?= htmlspecialchars($u['role']??'user') ?></span></td>
                <td><?= htmlspecialchars($u['level_name']??'') ?></td>
                <td class="fw-bold"><?= (int)($u['points']??0) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/usuarios/editar/<?= (int)$u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <?php if ($u['id'] != 1): ?>
                    <form method="post" action="<?= BASE_URL ?>admin/usuarios/excluir/<?= (int)$u['id'] ?>" class="d-inline" onsubmit="return confirm('Excluir?');">
                        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
