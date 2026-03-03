<?php
$currentUser = $currentUser ?? null;
$pageTitle = $pageTitle ?? 'Green Air';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Green Air</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="<?= $bodyClass ?? '' ?>">
<header class="site-header">
    <div class="container header-inner">
        <a href="<?= BASE_URL ?>" class="logo">
            <span class="logo-icon">🌳</span>
            <span class="logo-text">Green Air</span>
        </a>
        <nav class="nav-main">
            <a href="<?= BASE_URL ?>mapa">Mapa</a>
            <?php if ($currentUser): ?>
                <a href="<?= BASE_URL ?>painel">Painel</a>
                <a href="<?= BASE_URL ?>cadastrar-arvore">Cadastrar árvore</a>
                <a href="<?= BASE_URL ?>minhas-arvores">Minhas árvores</a>
                <a href="<?= BASE_URL ?>ranking">Ranking</a>
                <a href="<?= BASE_URL ?>perfil">Perfil</a>
                <?php if ((int)($currentUser['level_id']) === 3): ?>
                    <a href="<?= BASE_URL ?>admin">Admin</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>logout" class="btn-logout">Sair</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>login">Entrar</a>
                <a href="<?= BASE_URL ?>registro" class="btn-register">Cadastrar</a>
            <?php endif; ?>
        </nav>
        <button type="button" class="nav-toggle" aria-label="Menu">☰</button>
    </div>
</header>
<main class="main-content">
