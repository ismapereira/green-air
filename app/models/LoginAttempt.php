<?php
/**
 * Model para controle de tentativas de login (rate limiting).
 */
class LoginAttempt extends Model
{
    /**
     * Registra uma tentativa de login.
     */
    public function record(string $email, string $ip): void
    {
        $this->execute(
            'INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)',
            [$email, $ip]
        );
    }

    /**
     * Conta tentativas recentes para um email/IP nos últimos N minutos.
     */
    public function recentCount(string $email, string $ip, int $minutes = 15): int
    {
        $r = $this->fetchOne(
            'SELECT COUNT(*) as c FROM login_attempts
             WHERE (email = ? OR ip_address = ?)
             AND attempted_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)',
            [$email, $ip, $minutes]
        );
        return (int)($r['c'] ?? 0);
    }

    /**
     * Verifica se o login está bloqueado.
     */
    public function isBlocked(string $email, string $ip, int $maxAttempts = 5, int $minutes = 15): bool
    {
        return $this->recentCount($email, $ip, $minutes) >= $maxAttempts;
    }

    /**
     * Limpa tentativas antigas (pode ser chamado periodicamente).
     */
    public function cleanup(int $olderThanHours = 24): void
    {
        $this->execute(
            'DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL ? HOUR)',
            [$olderThanHours]
        );
    }

    /**
     * Limpa registros de sucesso (após login bem-sucedido).
     */
    public function clearForEmail(string $email): void
    {
        $this->execute('DELETE FROM login_attempts WHERE email = ?', [$email]);
    }
}
