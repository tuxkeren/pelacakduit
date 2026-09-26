<?php

namespace PelacakDuit\Models;

use PDO;

class Transaction
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, c.color, a.name as account_name
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            ORDER BY t.date DESC, t.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, c.type as category_type, a.name as account_name
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getByType(string $type, int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, c.color, a.name as account_name
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            WHERE t.type = ?
            ORDER BY t.date DESC, t.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$type, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getByDateRange(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, c.type as category_type, c.color
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.date BETWEEN ? AND ?
            ORDER BY t.date DESC, t.created_at DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function create(int $categoryId, float $amount, string $type, ?string $description, string $date, int $accountId = 1): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO transactions (category_id, account_id, amount, type, description, date)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$categoryId, $accountId, $amount, $type, $description, $date]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $categoryId, float $amount, ?string $description, string $date, int $accountId = 1): bool
    {
        $stmt = $this->db->prepare("
            UPDATE transactions
            SET category_id = ?, account_id = ?, amount = ?, description = ?, date = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$categoryId, $accountId, $amount, $description, $date, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getSummary(): array
    {
        $stmt = $this->db->query("
            SELECT
                type,
                SUM(amount) as total
            FROM transactions
            WHERE date >= DATE('now', '-30 days')
            GROUP BY type
        ");
        $result = $stmt->fetchAll();
        
        $summary = ['income' => 0, 'expense' => 0];
        foreach ($result as $row) {
            $summary[$row['type']] = (float) $row['total'];
        }
        
        return $summary;
    }

    public function getCategoryBreakdown(string $type, int $days = 30): array
    {
        $stmt = $this->db->prepare("
            SELECT
                c.id,
                c.name,
                c.color,
                SUM(t.amount) as total,
                COUNT(t.id) as count
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.type = ? AND t.date >= DATE('now', ? || ' days')
            GROUP BY c.id, c.name, c.color
            ORDER BY total DESC
        ");
        $stmt->execute([$type, -$days]);
        return $stmt->fetchAll();
    }

    public function getCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM transactions");
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}
