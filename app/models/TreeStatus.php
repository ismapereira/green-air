<?php
class TreeStatus extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM tree_status ORDER BY name ASC');
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM tree_status WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $this->execute('INSERT INTO tree_status (name, description) VALUES (?, ?)',
            [$data['name'], $data['description'] ?? null]);
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->execute('UPDATE tree_status SET name = ?, description = ? WHERE id = ?',
            [$data['name'], $data['description'] ?? null, $id]);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM tree_status WHERE id = ?', [$id]);
    }
}
