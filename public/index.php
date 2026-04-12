<?php
/**
 * Front Controller - Green Air
 * Todas as requisições passam por aqui (com .htaccess rewrite)
 */

// Evitar que a pasta config seja acessível pela web
if (strpos($_SERVER['SCRIPT_FILENAME'] ?? '', 'config') !== false) {
    http_response_code(403);
    exit('Acesso negado.');
}

require __DIR__ . '/../config/env.php';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../config/app.php';

// Headers de segurança (CSP)
header("Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self' 'unsafe-inline' cdn.jsdelivr.net www.google.com www.gstatic.com unpkg.com; "
    . "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com unpkg.com; "
    . "img-src 'self' data: blob: tile.openstreetmap.org *.tile.openstreetmap.org api.openweathermap.org openweathermap.org; "
    . "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net; "
    . "connect-src 'self' api.openweathermap.org nominatim.openstreetmap.org www.google.com; "
    . "frame-src www.google.com;"
);
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

$routes = require ROOT_PATH . '/routes/web.php';

// Método e path
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);

// Remover BASE_PATH do path para comparação
$basePath = defined('BASE_PATH') ? BASE_PATH : '';
if ($basePath !== '' && strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath)) ?: '/';
}
$path = '/' . trim($path, '/');
if ($path !== '/') {
    $path = rtrim($path, '/');
}

// Servir uploads (árvores e usuários)
if (preg_match('#^/uploads/(trees|users)/([a-zA-Z0-9_.-]+)$#', $path, $m)) {
    $dir = $m[1] === 'trees' ? (ROOT_PATH . '/uploads/trees') : (ROOT_PATH . '/uploads/users');
    $file = $dir . '/' . $m[2];
    if (file_exists($file) && is_file($file)) {
        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

$routeKey = $method . ' ' . $path;
$params = [];

// Tentativa de match exato
$handler = $routes[$routeKey] ?? null;

// Match com parâmetros {id}
if ($handler === null) {
    foreach ($routes as $pattern => $target) {
        list($m, $p) = explode(' ', $pattern, 2);
        if ($m !== $method) continue;
        $regex = '#^' . preg_replace('#\{[^}]+\}#', '([^/]+)', $p) . '$#';
        if (preg_match($regex, $path, $matches)) {
            array_shift($matches);
            $params = $matches;
            $handler = $target;
            break;
        }
    }
}

if ($handler === null) {
    http_response_code(404);
    require ROOT_PATH . '/app/views/errors/404.php';
    exit;
}

list($controllerName, $action) = $handler;
if (!class_exists($controllerName)) {
    http_response_code(500);
    echo 'Controller não encontrado: ' . htmlspecialchars($controllerName);
    exit;
}

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    http_response_code(500);
    echo 'Ação não encontrada: ' . htmlspecialchars($action);
    exit;
}

$controller->$action(...$params);
