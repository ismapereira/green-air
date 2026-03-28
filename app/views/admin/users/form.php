<?php
$pageTitle = isset($edit) && $edit ? 'Editar Usuário' : 'Novo Usuário';
$user = $user ?? [];
$levels = $levels ?? [];
$edit = $edit ?? null;
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-person me-2"></i><?= $edit ? 'Editar Usuário' : 'Novo Usuário' ?></h4>
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="post" action="<?= $edit ? BASE_URL.'admin/usuarios/editar/'.(int)$edit['id'] : BASE_URL.'admin/usuarios' ?>">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <div class="mb-3"><label class="form-label">Nome</label><input type="text" name="name" class="form-control form-control-sm" required value="<?= htmlspecialchars($edit['name']??'') ?>"></div>
            <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control form-control-sm" required value="<?= htmlspecialchars($edit['email']??'') ?>"></div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Nível</label>
                    <select name="level_id" class="form-select form-select-sm">
                        <?php foreach ($levels as $lv): ?>
                            <option value="<?= (int)$lv['id'] ?>" <?= ($edit && (int)$edit['level_id']===(int)$lv['id'])?'selected':'' ?>><?= htmlspecialchars($lv['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select form-select-sm">
                        <option value="user" <?= ($edit && ($edit['role']??'')==='user')?'selected':'' ?>>Usuário</option>
                        <option value="moderator" <?= ($edit && ($edit['role']??'')==='moderator')?'selected':'' ?>>Moderador</option>
                        <option value="admin" <?= ($edit && ($edit['role']??'')==='admin')?'selected':'' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <?php if ($edit): ?>
            <div class="mb-3"><label class="form-label">Pontos</label><input type="number" name="points" class="form-control form-control-sm" value="<?= (int)($edit['points']??0) ?>"></div>
            <div class="mb-3"><label class="form-label">Nova senha (vazio = manter)</label><input type="password" name="password" class="form-control form-control-sm"></div>
            <?php else: ?>
            <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control form-control-sm" required></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i><?= $edit ? 'Salvar' : 'Criar' ?></button>
            <a href="<?= BASE_URL ?>admin/usuarios" class="btn btn-outline-secondary btn-sm">Voltar</a>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
