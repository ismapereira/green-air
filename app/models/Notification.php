<?php
/**
 * Model para notificações do usuário.
 */
class Notification extends Model
{
    public function create(int $userId, string $type, string $title, string $message, ?string $link = null): int
    {
        $this->execute(
            'INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)',
            [$userId, $type, $title, $message, $link]
        );
        return (int) $this->lastInsertId();
    }

    public function byUser(int $userId, int $limit = 20): array
    {
        return $this->fetchAll(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?',
            [$userId, $limit]
        );
    }

    public function unreadCount(int $userId): int
    {
        $r = $this->fetchOne(
            'SELECT COUNT(*) as c FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        return (int)($r['c'] ?? 0);
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return $this->execute(
            'UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?',
            [$id, $userId]
        );
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->execute(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
    }
}
