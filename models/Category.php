<?php

namespace PelacakDuit\Models;

use PDO;

class Category
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getByType(string $type): array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE type = ? ORDER BY name ASC");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $type, string $color = '#3B82F6'): int
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name, type, color) VALUES (?, ?, ?)");
        $stmt->execute([$name, $type, $color]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $color): bool
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, color = ? WHERE id = ?");
        return $stmt->execute([$name, $color, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
