<?php
/**
 * Model para sugestões colaborativas da comunidade.
 */
class CommunitySuggestion extends Model
{
    public function create(int $userId, string $category, string $title, string $description): int
    {
        $this->execute(
            'INSERT INTO community_suggestions (user_id, category, title, description) VALUES (?, ?, ?, ?)',
            [$userId, $category, $title, $description]
        );
        return (int) $this->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT cs.*, u.name as user_name, u.email as user_email,
                    r.name as reviewer_name
             FROM community_suggestions cs
             JOIN users u ON cs.user_id = u.id
             LEFT JOIN users r ON cs.reviewed_by = r.id
             WHERE cs.id = ?',
            [$id]
        );
    }

    public function all(string $status = 'all', string $category = 'all', int $limit = 100): array
    {
        $sql = 'SELECT cs.*, u.name as user_name
                FROM community_suggestions cs
                JOIN users u ON cs.user_id = u.id';
        $params = [];
        $conditions = [];

        if ($status !== 'all') {
            $conditions[] = 'cs.status = ?';
            $params[] = $status;
        }
        if ($category !== 'all') {
            $conditions[] = 'cs.category = ?';
            $params[] = $category;
        }
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY cs.created_at DESC LIMIT ?';
        $params[] = $limit;
        return $this->fetchAll($sql, $params);
    }

    public function byUser(int $userId, int $limit = 50): array
    {
        return $this->fetchAll(
            'SELECT * FROM community_suggestions WHERE user_id = ? ORDER BY created_at DESC LIMIT ?',
            [$userId, $limit]
        );
    }

    public function pendingCount(): int
    {
        $r = $this->fetchOne("SELECT COUNT(*) as c FROM community_suggestions WHERE status = 'pending'");
        return (int)($r['c'] ?? 0);
    }

    public function countByCategory(): array
    {
        return $this->fetchAll(
            "SELECT category, COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
             FROM community_suggestions GROUP BY category ORDER BY total DESC"
        );
    }

    public function respond(int $id, int $reviewerId, string $status, ?string $response = null): bool
    {
        return $this->execute(
            'UPDATE community_suggestions SET status = ?, admin_response = ?, reviewed_by = ?, reviewed_at = NOW() WHERE id = ?',
            [$status, $response, $reviewerId, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM community_suggestions WHERE id = ?', [$id]);
    }
}
