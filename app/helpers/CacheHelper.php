<?php
/**
 * Cache simples baseado em arquivo para respostas de API.
 * Armazena em storage/cache/
 */
class CacheHelper
{
    private static ?string $cacheDir = null;

    private static function dir(): string
    {
        if (self::$cacheDir === null) {
            self::$cacheDir = ROOT_PATH . '/storage/cache';
            if (!is_dir(self::$cacheDir)) {
                mkdir(self::$cacheDir, 0755, true);
            }
        }
        return self::$cacheDir;
    }

    /**
     * Busca um valor do cache.
     * @return mixed|null  Dados do cache ou null se expirado/inexistente
     */
    public static function get(string $key)
    {
        $file = self::dir() . '/' . md5($key) . '.cache';
        if (!is_file($file)) return null;

        $data = @file_get_contents($file);
        if ($data === false) return null;

        $entry = @json_decode($data, true);
        if (!$entry || !isset($entry['expires'], $entry['value'])) return null;

        if (time() > $entry['expires']) {
            @unlink($file);
            return null;
        }

        return $entry['value'];
    }

    /**
     * Armazena um valor no cache.
     * @param int $ttl  Tempo de vida em segundos (padrão: 600 = 10 min)
     */
    public static function set(string $key, $value, int $ttl = 600): void
    {
        $file = self::dir() . '/' . md5($key) . '.cache';
        $entry = [
            'expires' => time() + $ttl,
            'value'   => $value
        ];
        @file_put_contents($file, json_encode($entry, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    /**
     * Remove uma entrada do cache.
     */
    public static function delete(string $key): void
    {
        $file = self::dir() . '/' . md5($key) . '.cache';
        if (is_file($file)) @unlink($file);
    }
}
