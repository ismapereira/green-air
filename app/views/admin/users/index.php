<?php
$pageTitle = 'Usuários';
$user = $user ?? [];
$users = $users ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Usuários</h1>
<p><a href="<?= BASE_URL ?>admin/usuarios/novo" class="btn btn-primary">Novo usuário</a></p>
<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Nível</th><th>Pontos</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['level_name'] ?? '') ?></td>
                <td><?= (int)($u['points'] ?? 0) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/usuarios/editar/<?= (int)$u['id'] ?>" class="btn btn-small">Editar</a>
                    <?php if ($u['id'] != 1): ?>
                    <form method="post" action="<?= BASE_URL ?>admin/usuarios/excluir/<?= (int)$u['id'] ?>" style="display:inline" onsubmit="return confirm('Excluir este usuário?');">
                        <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>
</body>
</html>
