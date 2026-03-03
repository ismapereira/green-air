<?php
$pageTitle = 'Configurações';
$user = $user ?? [];
$settings = $settings ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h1>Configurações gerais</h1>
<form method="post" action="<?= BASE_URL ?>admin/configuracoes" class="admin-form">
    <?php
    $keys = ['site_name', 'openweather_api_key', 'default_city'];
    foreach ($keys as $k):
        $v = $settings[$k] ?? '';
    ?>
    <label><span><?= htmlspecialchars(str_replace('_', ' ', ucfirst($k))) ?></span><input type="text" name="setting_<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>"></label>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
</main>
</body>
</html>
