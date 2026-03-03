<?php
class TreeSpecies extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM tree_species ORDER BY name ASC');
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM tree_species WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $this->execute('INSERT INTO tree_species (name, scientific_name) VALUES (?, ?)',
            [$data['name'], $data['scientific_name'] ?? null]);
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->execute('UPDATE tree_species SET name = ?, scientific_name = ? WHERE id = ?',
            [$data['name'], $data['scientific_name'] ?? null, $id]);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM tree_species WHERE id = ?', [$id]);
    }
}
