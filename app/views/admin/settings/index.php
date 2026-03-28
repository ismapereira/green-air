<?php
$pageTitle = 'Configurações';
$user = $user ?? [];
$settings = $settings ?? [];
require ROOT_PATH . '/app/views/admin/layout.php';
?>
<h4 class="fw-bold mb-3"><i class="bi bi-gear me-2"></i>Configurações Gerais</h4>
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>admin/configuracoes">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
            <?php
            $keys = ['site_name', 'openweather_api_key', 'default_city'];
            foreach ($keys as $k):
                $v = $settings[$k] ?? '';
            ?>
            <div class="mb-3">
                <label class="form-label"><?= htmlspecialchars(str_replace('_',' ',ucfirst($k))) ?></label>
                <input type="text" name="setting_<?= htmlspecialchars($k) ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($v) ?>">
            </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Salvar</button>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
