<?php
/**
 * Carrega variáveis do arquivo .env para o ambiente (putenv + $_ENV).
 * Uso: env('CHAVE', 'valor_padrao')
 */

$rootPath = dirname(__DIR__);
$envFile = $rootPath . '/.env';

if (!is_file($envFile)) {
    return;
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    return;
}

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || strpos($line, '#') === 0) {
        continue;
    }
    if (strpos($line, '=') === false) {
        continue;
    }
    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);
    if ($name === '') {
        continue;
    }
    if (preg_match('/^(["\'])(.*)\1$/', $value, $m)) {
        $value = $m[2];
    }
    putenv("$name=$value");
    $_ENV[$name] = $value;
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        return $_ENV[$key] ?? $default;
    }
}
