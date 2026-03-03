<?php
$pageTitle = isset($edit) && $edit ? 'Editar usuário' : 'Novo usuário';
$user = $user ?? [];
$levels = $levels ?? [];
$edit = $edit ?? null;
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1><?= $edit ? 'Editar usuário' : 'Novo usuário' ?></h1>
<form method="post" action="<?= $edit ? BASE_URL . 'admin/usuarios/editar/' . (int)$edit['id'] : BASE_URL . 'admin/usuarios' ?>" class="admin-form">
    <label><span>Nome</span><input type="text" name="name" required value="<?= htmlspecialchars($edit['name'] ?? '') ?>"></label>
    <label><span>E-mail</span><input type="email" name="email" required value="<?= htmlspecialchars($edit['email'] ?? '') ?>"></label>
    <label><span>Nível</span>
        <select name="level_id">
            <?php foreach ($levels as $lv): ?>
                <option value="<?= (int)$lv['id'] ?>" <?= ($edit && (int)$edit['level_id'] === (int)$lv['id']) ? 'selected' : '' ?>><?= htmlspecialchars($lv['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if ($edit): ?>
    <label><span>Pontos</span><input type="number" name="points" value="<?= (int)($edit['points'] ?? 0) ?>"></label>
    <label><span>Nova senha (deixe em branco para manter)</span><input type="password" name="password"></label>
    <?php else: ?>
    <label><span>Senha</span><input type="password" name="password" <?= $edit ? '' : 'required' ?>></label>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary"><?= $edit ? 'Salvar' : 'Criar' ?></button>
</form>
<p><a href="<?= BASE_URL ?>admin/usuarios">Voltar</a></p>
</main>
</body>
</html>
