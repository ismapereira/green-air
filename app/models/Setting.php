<?php
class Setting extends Model
{
    public function get(string $key, $default = null)
    {
        $row = $this->fetchOne('SELECT setting_value FROM settings WHERE setting_key = ?', [$key]);
        return $row ? $row['setting_value'] : $default;
    }

    public function set(string $key, string $value): void
    {
        $this->execute(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
            [$key, $value]
        );
    }

    public function all(): array
    {
        $rows = $this->fetchAll('SELECT setting_key, setting_value FROM settings');
        $out = [];
        foreach ($rows as $r) {
            $out[$r['setting_key']] = $r['setting_value'];
        }
        return $out;
    }
}
