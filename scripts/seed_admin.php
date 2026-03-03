<?php
/**
 * Cria o usuário administrador se não existir.
 * Execute uma vez: php scripts/seed_admin.php
 */
$root = dirname(dirname(__FILE__));
require $root . '/config/env.php';
require $root . '/config/database.php';
require $root . '/config/app.php';

$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute(['admin@greenair.com']);
if ($stmt->fetch()) {
    echo "Admin já existe.\n";
    exit(0);
}
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->prepare('INSERT INTO users (name, email, password, level_id) VALUES (?, ?, ?, 3)')
    ->execute(['Administrador', 'admin@greenair.com', $hash]);
echo "Admin criado: admin@greenair.com / admin123\n";
