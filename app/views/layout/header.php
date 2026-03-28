<?php
$pageTitle = $pageTitle ?? 'Green Air';
$currentUser = $currentUser ?? ($user ?? null);
$unreadNotifications = $unreadNotifications ?? 0;
$csrfToken = $csrfToken ?? '';

// Detectar rota ativa para bottom nav
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$basePath = BASE_PATH ?: '';
$route = str_replace($basePath, '', $currentPath);
$route = '/' . ltrim($route, '/');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Green Air — Mapeamento colaborativo de árvores urbanas. Cadastre, localize e contribua para uma cidade mais verde.">
    <meta name="theme-color" content="#059669">
    <title><?= htmlspecialchars($pageTitle) ?> | Green Air</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Green Air CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg ga-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <span class="brand-icon">🌳</span> Green Air
        </a>

        <div class="d-flex align-items-center gap-2 d-lg-none">
            <?php if ($currentUser): ?>
            <a href="<?= BASE_URL ?>api/notificacoes" class="nav-link position-relative p-1" id="mobile-notif-btn" title="Notificações">
                <i class="bi bi-bell fs-5"></i>
                <?php if ($unreadNotifications > 0): ?>
                    <span class="notification-badge"><?= $unreadNotifications ?></span>
                <?php endif; ?>
            </a>
            <?php endif; ?>
            <button class="theme-toggle" id="theme-toggle-mobile" title="Alternar tema">
                <i class="bi bi-moon-fill"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= $route === '/' ? 'active' : '' ?>" href="<?= BASE_URL ?>">
                        <i class="bi bi-house me-1"></i>Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $route === '/mapa' ? 'active' : '' ?>" href="<?= BASE_URL ?>mapa">
                        <i class="bi bi-map me-1"></i>Mapa
                    </a>
                </li>
                <?php if ($currentUser): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $route === '/painel' ? 'active' : '' ?>" href="<?= BASE_URL ?>painel">
                        <i class="bi bi-grid me-1"></i>Painel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $route === '/ranking' ? 'active' : '' ?>" href="<?= BASE_URL ?>ranking">
                        <i class="bi bi-trophy me-1"></i>Ranking
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link position-relative" href="#" id="desktop-notif-btn" title="Notificações">
                        <i class="bi bi-bell"></i>
                        <?php if ($unreadNotifications > 0): ?>
                            <span class="notification-badge"><?= $unreadNotifications ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <?php if (!empty($currentUser['photo'])): ?>
                            <img src="<?= BASE_URL ?>uploads/users/<?= htmlspecialchars($currentUser['photo']) ?>" alt="" class="avatar-sm">
                        <?php else: ?>
                            <span class="avatar-placeholder-sm"><?= strtoupper(mb_substr($currentUser['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <span class="d-none d-xl-inline"><?= htmlspecialchars($currentUser['name']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>perfil"><i class="bi bi-person me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>minhas-arvores"><i class="bi bi-tree me-2"></i>Minhas Árvores</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>cadastrar-arvore"><i class="bi bi-plus-circle me-2"></i>Cadastrar Árvore</a></li>
                        <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>admin"><i class="bi bi-shield-check me-2"></i>Admin</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="post" action="<?= BASE_URL ?>logout" class="px-3 py-1">
                                <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
                                <button type="submit" class="dropdown-item p-0"><i class="bi bi-box-arrow-right me-2"></i>Sair</button>
                            </form>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>login"><i class="bi bi-box-arrow-in-right me-1"></i>Entrar</a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="btn btn-success btn-sm ms-2" href="<?= BASE_URL ?>registro">Cadastre-se</a>
                </li>
                <?php endif; ?>
                <li class="nav-item d-none d-lg-block">
                    <button class="theme-toggle ms-2" id="theme-toggle-desktop" title="Alternar tema">
                        <i class="bi bi-moon-fill"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: var(--ga-header-h);"></div>

<!-- Toast Container -->
<div class="ga-toast-container" id="toast-container"></div>

<main>
