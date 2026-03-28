<?php
/**
 * Model para sugestões de atualização de árvores.
 */
class TreeSuggestion extends Model
{
    public function create(int $userId, int $treeId, string $suggestion): int
    {
        $this->execute(
            'INSERT INTO tree_suggestions (user_id, tree_id, suggestion) VALUES (?, ?, ?)',
            [$userId, $treeId, $suggestion]
        );
        return (int) $this->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT s.*, u.name as user_name, t.id as tree_id_ref,
                    ts.name as species_name
             FROM tree_suggestions s
             JOIN users u ON s.user_id = u.id
             JOIN trees t ON s.tree_id = t.id
             JOIN tree_species ts ON t.species_id = ts.id
             WHERE s.id = ?',
            [$id]
        );
    }

    public function all(string $status = 'all', int $limit = 100): array
    {
        $sql = 'SELECT s.*, u.name as user_name, t.id as tree_id_ref,
                       ts.name as species_name
                FROM tree_suggestions s
                JOIN users u ON s.user_id = u.id
                JOIN trees t ON s.tree_id = t.id
                JOIN tree_species ts ON t.species_id = ts.id';
        $params = [];
        if ($status !== 'all') {
            $sql .= ' WHERE s.status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY s.created_at DESC LIMIT ?';
        $params[] = $limit;
        return $this->fetchAll($sql, $params);
    }

    public function pendingCount(): int
    {
        $r = $this->fetchOne("SELECT COUNT(*) as c FROM tree_suggestions WHERE status = 'pending'");
        return (int)($r['c'] ?? 0);
    }

    public function approve(int $id, int $reviewerId): bool
    {
        return $this->execute(
            "UPDATE tree_suggestions SET status = 'approved', reviewed_by = ?, reviewed_at = NOW() WHERE id = ?",
            [$reviewerId, $id]
        );
    }

    public function reject(int $id, int $reviewerId): bool
    {
        return $this->execute(
            "UPDATE tree_suggestions SET status = 'rejected', reviewed_by = ?, reviewed_at = NOW() WHERE id = ?",
            [$reviewerId, $id]
        );
    }
}
