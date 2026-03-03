<?php
$adminUser = $adminUser ?? [];
$pageTitle = $pageTitle ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Admin Green Air</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="container">
        <a href="<?= BASE_URL ?>admin" class="admin-logo">Green Air Admin</a>
        <nav>
            <a href="<?= BASE_URL ?>admin">Dashboard</a>
            <a href="<?= BASE_URL ?>admin/usuarios">Usuários</a>
            <a href="<?= BASE_URL ?>admin/arvores">Árvores</a>
            <a href="<?= BASE_URL ?>admin/especies">Espécies</a>
            <a href="<?= BASE_URL ?>admin/status">Status</a>
            <a href="<?= BASE_URL ?>admin/contribuicoes">Contribuições</a>
            <a href="<?= BASE_URL ?>admin/configuracoes">Configurações</a>
            <a href="<?= BASE_URL ?>painel">Voltar ao site</a>
        </nav>
    </div>
</header>
<main class="admin-main container">
<?php if (!empty($_SESSION['admin_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['admin_success']) ?></div>
    <?php unset($_SESSION['admin_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['admin_error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['admin_error']) ?></div>
    <?php unset($_SESSION['admin_error']); ?>
<?php endif; ?>
