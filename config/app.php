<?php
/**
 * Configurações gerais da aplicação
 * Green Air v2.0 - Mapeamento de Árvores Urbanas
 */

// Base URL — remover /public do path para URLs limpas
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$basePath = $script ?: '';
// Remover /public do final, pois o .htaccess raiz já redireciona internamente
$basePath = preg_replace('#/public$#', '', $basePath);
define('BASE_URL', $protocol . '://' . $host . $basePath . '/');
define('BASE_PATH', $basePath);

// Caminhos físicos
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('UPLOAD_TREES', UPLOAD_PATH . '/trees');
define('UPLOAD_USERS', UPLOAD_PATH . '/users');
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Pontuação e níveis
define('POINTS_NEW_TREE', 10);
define('POINTS_SUGGESTION_APPROVED', 3);
define('LEVEL_BRONZE', 1);
define('LEVEL_PRATA', 2);
define('LEVEL_OURO', 3);

// Upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Sessão
define('SESSION_LIFETIME', 7200);
define('SESSION_NAME', 'GREENAIR_SID');

// OpenWeather API
define('OPENWEATHER_API_KEY', env('OPENWEATHER_API_KEY', ''));
define('OPENWEATHER_CITY', env('OPENWEATHER_CITY', 'São Paulo'));

// Cache
define('CACHE_CLIMATE_TTL', 600); // 10 minutos

// Rate limiting
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);

// Fuso e locale
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR.UTF-8');

// Iniciar sessão centralizada com segurança
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', 1);
    if ($protocol === 'https') {
        ini_set('session.cookie_secure', 1);
    }
    session_name(SESSION_NAME);
    session_start();
}

// Autoload simples para classes em app/
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/app/controllers/' . $class . '.php',
        ROOT_PATH . '/app/models/' . $class . '.php',
        ROOT_PATH . '/app/helpers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
