<?php

namespace PelacakDuit\Models;

use PDO;

class Account
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM accounts ORDER BY id");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $type = 'bank', float $balance = 0): int
    {
        $stmt = $this->db->prepare("INSERT INTO accounts (name, type, balance) VALUES (?, ?, ?)");
        $stmt->execute([$name, $type, $balance]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $type, float $balance): bool
    {
        $stmt = $this->db->prepare("UPDATE accounts SET name = ?, type = ?, balance = ? WHERE id = ?");
        return $stmt->execute([$name, $type, $balance, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM accounts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getSummary(): array
    {
        $stmt = $this->db->query("
            SELECT 
                a.id,
                a.name,
                a.type,
                a.balance as initial_balance,
                COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0) as total_expense,
                (a.balance + COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END), 0)) as current_balance,
                COUNT(t.id) as transaction_count
            FROM accounts a
            LEFT JOIN transactions t ON a.id = t.account_id
            GROUP BY a.id, a.name, a.type, a.balance
            ORDER BY current_balance DESC
        ");
        return $stmt->fetchAll();
    }
}
