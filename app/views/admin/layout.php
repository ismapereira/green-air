<?php
$adminUser = $adminUser ?? $user ?? [];
$pageTitle = $pageTitle ?? 'Admin';
$csrfToken = $csrfToken ?? '';
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Admin Green Air</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        body { font-family: var(--ga-font); }
        .admin-sidebar {
            width: 250px; min-height: 100vh; background: #0B1120;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: fixed; left: 0; top: 0;
            padding: 1rem 0; z-index: 1060;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .admin-sidebar .brand {
            padding: 0.5rem 1.25rem 1.25rem;
            font-weight: 800; font-size: 1.1rem; color: #10B981;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .admin-sidebar .nav-link {
            color: #94A3B8; padding: 0.65rem 1.25rem; font-size: 0.88rem;
            display: flex; align-items: center; gap: 0.65rem;
            border-radius: 0; transition: all 0.2s; text-decoration: none;
        }
        .admin-sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .admin-sidebar .nav-link.active { color: #10B981; background: rgba(16,185,129,0.08); border-right: 3px solid #10B981; }
        .admin-sidebar .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }
        .admin-content { margin-left: 250px; padding: 1.5rem; min-height: 100vh; }

        /* Mobile sidebar */
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-content { margin-left: 0; }
        }

        .admin-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 1055;
        }
        .admin-backdrop.show { display: block; }

        .admin-topbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; gap: 0.5rem;
        }
        .admin-topbar .menu-btn {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            color: #94A3B8; padding: 0.4rem 0.6rem; border-radius: 8px;
            cursor: pointer; font-size: 1.2rem; transition: all 0.2s;
        }
        .admin-topbar .menu-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }

        .admin-sidebar .sidebar-close {
            display: none; position: absolute; top: 0.75rem; right: 0.75rem;
            background: none; border: none; color: #94A3B8; font-size: 1.2rem;
            cursor: pointer; padding: 0.25rem;
        }
        .admin-sidebar .sidebar-close:hover { color: #fff; }
        @media (max-width: 991px) {
            .admin-sidebar .sidebar-close { display: block; }
        }
    </style>
</head>
<body>

<!-- Backdrop for mobile -->
<div class="admin-backdrop" id="admin-backdrop"></div>

<!-- Sidebar -->
<nav class="admin-sidebar" id="admin-sidebar">
    <button class="sidebar-close" id="sidebar-close"><i class="bi bi-x-lg"></i></button>
    <div class="brand"><i class="bi bi-tree-fill me-1"></i> Green Air Admin</div>
    <a href="<?= BASE_URL ?>admin" class="nav-link <?= strpos($currentPath, '/admin') !== false && strpos($currentPath, '/admin/') === false ? 'active' : '' ?>"><i class="bi bi-grid"></i>Dashboard</a>
    <a href="<?= BASE_URL ?>admin/usuarios" class="nav-link <?= strpos($currentPath, 'usuarios') !== false ? 'active' : '' ?>"><i class="bi bi-people"></i>Usuários</a>
    <a href="<?= BASE_URL ?>admin/arvores" class="nav-link <?= strpos($currentPath, 'arvores') !== false ? 'active' : '' ?>"><i class="bi bi-tree"></i>Árvores</a>
    <a href="<?= BASE_URL ?>admin/especies" class="nav-link <?= strpos($currentPath, 'especies') !== false ? 'active' : '' ?>"><i class="bi bi-flower1"></i>Espécies</a>
    <a href="<?= BASE_URL ?>admin/status" class="nav-link <?= strpos($currentPath, '/status') !== false ? 'active' : '' ?>"><i class="bi bi-shield-check"></i>Status</a>
    <a href="<?= BASE_URL ?>admin/sugestoes" class="nav-link <?= strpos($currentPath, 'sugestoes') !== false ? 'active' : '' ?>"><i class="bi bi-chat-square-text"></i>Sugestões</a>
    <a href="<?= BASE_URL ?>admin/comunidade" class="nav-link <?= strpos($currentPath, 'comunidade') !== false ? 'active' : '' ?>"><i class="bi bi-people"></i>Comunidade</a>
    <a href="<?= BASE_URL ?>admin/contribuicoes" class="nav-link <?= strpos($currentPath, 'contribuicoes') !== false ? 'active' : '' ?>"><i class="bi bi-clock-history"></i>Contribuições</a>
    <a href="<?= BASE_URL ?>admin/configuracoes" class="nav-link <?= strpos($currentPath, 'configuracoes') !== false ? 'active' : '' ?>"><i class="bi bi-gear"></i>Configurações</a>
    <hr style="border-color:rgba(255,255,255,0.06);margin:1rem 1.25rem">
    <a href="<?= BASE_URL ?>painel" class="nav-link"><i class="bi bi-arrow-left"></i>Voltar ao site</a>
</nav>

<div class="admin-content">
    <!-- Top bar -->
    <div class="admin-topbar">
        <button class="menu-btn d-lg-none" id="admin-menu-btn" title="Menu">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-none d-lg-block"></div>
        <div class="d-flex align-items-center gap-2 small text-muted">
            <i class="bi bi-person-circle"></i>
            <span class="d-none d-sm-inline"><?= htmlspecialchars($adminUser['name'] ?? 'Admin') ?></span>
        </div>
    </div>

    <?php if (!empty($_SESSION['admin_success'])): ?>
        <div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['admin_success']) ?></div>
        <?php unset($_SESSION['admin_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['admin_error'])): ?>
        <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['admin_error']) ?></div>
        <?php unset($_SESSION['admin_error']); ?>
    <?php endif; ?>

<script>
(function() {
    var sidebar = document.getElementById('admin-sidebar');
    var backdrop = document.getElementById('admin-backdrop');
    var menuBtn = document.getElementById('admin-menu-btn');
    var closeBtn = document.getElementById('sidebar-close');
    function openSidebar() { sidebar.classList.add('show'); backdrop.classList.add('show'); }
    function closeSidebar() { sidebar.classList.remove('show'); backdrop.classList.remove('show'); }
    if (menuBtn) menuBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
})();
</script>
