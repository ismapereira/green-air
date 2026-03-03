<?php
class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT u.*, ul.name as level_name FROM users u 
            JOIN user_levels ul ON u.level_id = ul.id WHERE u.email = ?', [$email]);
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT u.*, ul.name as level_name FROM users u 
            JOIN user_levels ul ON u.level_id = ul.id WHERE u.id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password, photo, level_id, points) VALUES (?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['photo'] ?? null,
            $data['level_id'] ?? 1,
            $data['points'] ?? 0
        ]);
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];
        foreach (['name', 'email', 'photo', 'level_id', 'points'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = ?";
                $params[] = $data[$f];
            }
        }
        if (isset($data['password']) && $data['password'] !== '') {
            $fields[] = 'password = ?';
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (empty($fields)) return true;
        $params[] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        return $this->execute($sql, $params);
    }

    public function updatePasswordByEmail(string $email, string $hash): bool
    {
        return $this->execute('UPDATE users SET password = ? WHERE email = ?', [$hash, $email]);
    }

    public function all(string $order = 'name'): array
    {
        $allowed = ['name', 'email', 'created_at', 'points'];
        if (!in_array($order, $allowed)) $order = 'name';
        return $this->fetchAll("SELECT u.*, ul.name as level_name FROM users u 
            JOIN user_levels ul ON u.level_id = ul.id ORDER BY u.$order ASC");
    }

    public function addPoints(int $userId, int $points): void
    {
        $this->execute('UPDATE users SET points = points + ? WHERE id = ?', [$points, $userId]);
        $this->recalculateLevel($userId);
    }

    public function recalculateLevel(int $userId): void
    {
        $user = $this->findById($userId);
        if (!$user) return;
        $levels = $this->fetchAll('SELECT id, min_points FROM user_levels ORDER BY min_points DESC');
        $newLevel = 1;
        foreach ($levels as $lv) {
            if ($user['points'] >= (int)$lv['min_points']) {
                $newLevel = (int)$lv['id'];
                break;
            }
        }
        $this->execute('UPDATE users SET level_id = ? WHERE id = ?', [$newLevel, $userId]);
    }

    public function topContributors(int $limit = 10): array
    {
        return $this->fetchAll('SELECT u.*, ul.name as level_name FROM users u 
            JOIN user_levels ul ON u.level_id = ul.id ORDER BY u.points DESC LIMIT ?', [$limit]);
    }

    public function count(): int
    {
        $r = $this->fetchOne('SELECT COUNT(*) as c FROM users');
        return (int)($r['c'] ?? 0);
    }
}
