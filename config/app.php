<?php
/**
 * Configurações gerais da aplicação
 * Green Air - Mapeamento de Árvores Urbanas
 */

// Base URL (pasta public é o document root ou base da aplicação)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$basePath = $script ?: '';
define('BASE_URL', $protocol . '://' . $host . $basePath . '/');
define('BASE_PATH', $basePath);

// Caminhos físicos (raiz do projeto = parent de /public)
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('UPLOAD_TREES', UPLOAD_PATH . '/trees');
define('UPLOAD_USERS', UPLOAD_PATH . '/users');

// Pontuação e níveis
define('POINTS_NEW_TREE', 10);
define('POINTS_SUGGESTION_APPROVED', 3);
define('LEVEL_BRONZE', 1);
define('LEVEL_PRATA', 2);
define('LEVEL_OURO', 3);

// Upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Sessão
define('SESSION_LIFETIME', 7200); // 2 horas
define('SESSION_NAME', 'GREENAIR_SID');

// OpenWeather API (variáveis no .env)
define('OPENWEATHER_API_KEY', env('OPENWEATHER_API_KEY', ''));
define('OPENWEATHER_CITY', env('OPENWEATHER_CITY', 'São Paulo'));

// Fuso e locale
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR.UTF-8');

// Autoload simples para classes em app/
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/app/controllers/' . $class . '.php',
        ROOT_PATH . '/app/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
