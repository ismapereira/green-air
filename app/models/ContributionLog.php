<?php
class ContributionLog extends Model
{
    public const ACTION_ADD_TREE = 'ADD_TREE';
    public const ACTION_SUGGEST_UPDATE = 'SUGGEST_UPDATE';
    public const ACTION_EDIT_TREE = 'EDIT_TREE';

    public function log(int $userId, ?int $treeId, string $action, int $points = 0): void
    {
        $this->execute(
            'INSERT INTO contributions_log (user_id, tree_id, action, points_awarded) VALUES (?, ?, ?, ?)',
            [$userId, $treeId, $action, $points]
        );
    }

    public function all(int $limit = 100): array
    {
        return $this->fetchAll(
            'SELECT c.*, u.name as user_name, u.email FROM contributions_log c 
             JOIN users u ON c.user_id = u.id 
             ORDER BY c.created_at DESC LIMIT ?',
            [$limit]
        );
    }

    public function byUser(int $userId, int $limit = 50): array
    {
        return $this->fetchAll(
            'SELECT * FROM contributions_log WHERE user_id = ? ORDER BY created_at DESC LIMIT ?',
            [$userId, $limit]
        );
    }
}
