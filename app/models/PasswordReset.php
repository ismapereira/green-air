<?php
class PasswordReset extends Model
{
    public function create(string $email, string $token): void
    {
        $this->execute('DELETE FROM password_resets WHERE email = ?', [$email]);
        $this->execute('INSERT INTO password_resets (email, token) VALUES (?, ?)', [$email, $token]);
    }

    public function findValidToken(string $token): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)',
            [$token]
        );
    }

    public function deleteByToken(string $token): void
    {
        $this->execute('DELETE FROM password_resets WHERE token = ?', [$token]);
    }
}
