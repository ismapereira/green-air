<?php
/**
 * Model para sistema de conquistas (badges).
 */
class Badge extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM badges ORDER BY id ASC');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne('SELECT * FROM badges WHERE slug = ?', [$slug]);
    }

    public function userBadges(int $userId): array
    {
        return $this->fetchAll(
            'SELECT b.*, ub.unlocked_at
             FROM user_badges ub
             JOIN badges b ON ub.badge_id = b.id
             WHERE ub.user_id = ?
             ORDER BY ub.unlocked_at DESC',
            [$userId]
        );
    }

    public function userBadgeCount(int $userId): int
    {
        $r = $this->fetchOne('SELECT COUNT(*) as c FROM user_badges WHERE user_id = ?', [$userId]);
        return (int)($r['c'] ?? 0);
    }

    public function hasBadge(int $userId, int $badgeId): bool
    {
        $r = $this->fetchOne(
            'SELECT 1 FROM user_badges WHERE user_id = ? AND badge_id = ?',
            [$userId, $badgeId]
        );
        return $r !== null;
    }

    public function unlock(int $userId, int $badgeId): bool
    {
        if ($this->hasBadge($userId, $badgeId)) {
            return false;
        }
        return $this->execute(
            'INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (?, ?)',
            [$userId, $badgeId]
        );
    }

    /**
     * Retorna all badges com status de desbloqueio para um usuário.
     */
    public function allWithStatus(int $userId): array
    {
        return $this->fetchAll(
            'SELECT b.*, ub.unlocked_at
             FROM badges b
             LEFT JOIN user_badges ub ON b.id = ub.badge_id AND ub.user_id = ?
             ORDER BY b.id ASC',
            [$userId]
        );
    }
}
