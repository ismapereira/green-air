<?php
class Tree extends Model
{
    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT t.*, ts.name as species_name, ts.scientific_name as species_scientific, 
                    tst.name as status_name, tst.description as status_description,
                    u.name as user_name
             FROM trees t
             JOIN tree_species ts ON t.species_id = ts.id
             JOIN tree_status tst ON t.status_id = tst.id
             JOIN users u ON t.user_id = u.id
             WHERE t.id = ?',
            [$id]
        );
    }

    public function all(array $filters = []): array
    {
        $sql = 'SELECT t.*, ts.name as species_name, tst.name as status_name, u.name as user_name 
                FROM trees t 
                JOIN tree_species ts ON t.species_id = ts.id 
                JOIN tree_status tst ON t.status_id = tst.id 
                JOIN users u ON t.user_id = u.id WHERE 1=1';
        $params = [];
        if (!empty($filters['species_id'])) {
            $sql .= ' AND t.species_id = ?';
            $params[] = $filters['species_id'];
        }
        if (!empty($filters['status_id'])) {
            $sql .= ' AND t.status_id = ?';
            $params[] = $filters['status_id'];
        }
        if (!empty($filters['address'])) {
            $sql .= ' AND t.address LIKE ?';
            $params[] = '%' . $filters['address'] . '%';
        }
        if (!empty($filters['size'])) {
            $sql .= ' AND t.size = ?';
            $params[] = $filters['size'];
        }
        $sql .= ' ORDER BY t.created_at DESC';
        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int)$filters['limit'];
        }
        return $this->fetchAll($sql, $params);
    }

    public function allForMap(array $filters = []): array
    {
        $sql = 'SELECT t.id, t.latitude, t.longitude, t.address, t.size, t.photo,
                    ts.name as species_name, tst.name as status_name
             FROM trees t
             JOIN tree_species ts ON t.species_id = ts.id
             JOIN tree_status tst ON t.status_id = tst.id WHERE 1=1';
        $params = [];
        if (!empty($filters['species_id'])) { $sql .= ' AND t.species_id = ?'; $params[] = $filters['species_id']; }
        if (!empty($filters['status_id'])) { $sql .= ' AND t.status_id = ?'; $params[] = $filters['status_id']; }
        if (!empty($filters['size'])) { $sql .= ' AND t.size = ?'; $params[] = $filters['size']; }
        if (!empty($filters['address'])) { $sql .= ' AND t.address LIKE ?'; $params[] = '%' . $filters['address'] . '%'; }
        return $this->fetchAll($sql, $params);
    }

    public function byUser(int $userId): array
    {
        return $this->fetchAll(
            'SELECT t.*, ts.name as species_name, tst.name as status_name 
             FROM trees t 
             JOIN tree_species ts ON t.species_id = ts.id 
             JOIN tree_status tst ON t.status_id = tst.id 
             WHERE t.user_id = ? ORDER BY t.created_at DESC',
            [$userId]
        );
    }

    public function create(array $data): int
    {
        $this->execute(
            'INSERT INTO trees (user_id, species_id, status_id, latitude, longitude, address, size, age_approx, observations, photo) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['user_id'],
                $data['species_id'],
                $data['status_id'],
                $data['latitude'],
                $data['longitude'],
                $data['address'] ?? null,
                $data['size'] ?? null,
                $data['age_approx'] ?? null,
                $data['observations'] ?? null,
                $data['photo']
            ]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = ['species_id', 'status_id', 'latitude', 'longitude', 'address', 'size', 'age_approx', 'observations'];
        $updates = [];
        $params = [];
        foreach ($fields as $f) {
            if (array_key_exists($f, $data)) {
                $updates[] = "$f = ?";
                $params[] = $data[$f];
            }
        }
        if (isset($data['photo'])) {
            $updates[] = 'photo = ?';
            $params[] = $data['photo'];
        }
        if (empty($updates)) return true;
        $params[] = $id;
        return $this->execute('UPDATE trees SET ' . implode(', ', $updates) . ' WHERE id = ?', $params);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM trees WHERE id = ?', [$id]);
    }

    public function count(): int
    {
        $r = $this->fetchOne('SELECT COUNT(*) as c FROM trees');
        return (int)($r['c'] ?? 0);
    }

    public function countByStatus(int $statusId): int
    {
        $r = $this->fetchOne('SELECT COUNT(*) as c FROM trees WHERE status_id = ?', [$statusId]);
        return (int)($r['c'] ?? 0);
    }

    public function countBySpecies(): array
    {
        return $this->fetchAll('SELECT species_id, COUNT(*) as total FROM trees GROUP BY species_id');
    }

    public function countByNeighborhood(): array
    {
        return $this->fetchAll(
            "SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(address, ',', 1), ',', -1)) as bairro, COUNT(*) as total 
             FROM trees WHERE address IS NOT NULL AND address != '' GROUP BY bairro ORDER BY total DESC LIMIT 20"
        );
    }

    public function riskCount(): int
    {
        $r = $this->fetchOne("SELECT COUNT(*) as c FROM trees t JOIN tree_status ts ON t.status_id = ts.id WHERE ts.name LIKE '%Risco%' OR ts.name LIKE '%Queda%'");
        return (int)($r['c'] ?? 0);
    }
}
