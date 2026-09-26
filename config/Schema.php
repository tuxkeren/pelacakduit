<?php

namespace PelacakDuit\Config;

use PDO;

class Schema
{
    public static function createTables(PDO $db, string $driver): void
    {
        if ($driver === 'sqlite') {
            $db->exec("PRAGMA foreign_keys = ON;");
        }

        // Tabel kategori
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id          INTEGER PRIMARY KEY " . ($driver === 'sqlite' ? 'AUTOINCREMENT' : 'AUTO_INCREMENT') . ",
            name        VARCHAR(100) NOT NULL,
            type        VARCHAR(10)  NOT NULL CHECK(type IN ('income','expense')),
            color       VARCHAR(7)   DEFAULT '#3B82F6',
            created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($sql);

        // Tabel akun / dompet
        $sql = "CREATE TABLE IF NOT EXISTS accounts (
            id          INTEGER PRIMARY KEY " . ($driver === 'sqlite' ? 'AUTOINCREMENT' : 'AUTO_INCREMENT') . ",
            name        VARCHAR(100) NOT NULL,
            type        VARCHAR(50)  DEFAULT 'bank',
            balance     DECIMAL(15,2) DEFAULT 0,
            created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($sql);

        // Insert data akun default jika kosong
        $countAcc = (int)$db->query("SELECT COUNT(*) FROM accounts")->fetchColumn();
        if ($countAcc === 0) {
            $defaultAccounts = [
                ['Tunai / Cash', 'cash', 0],
                ['Bank Mandiri', 'bank', 0],
                ['BSI (Bank Syariah Indonesia)', 'bank', 0],
                ['E-Wallet (GoPay/OVO)', 'ewallet', 0],
            ];
            $stmt = $db->prepare("INSERT INTO accounts (name, type, balance) VALUES (?, ?, ?)");
            foreach ($defaultAccounts as $acc) {
                $stmt->execute($acc);
            }
        }

        // Tabel transaksi
        $sql = "CREATE TABLE IF NOT EXISTS transactions (
            id          INTEGER PRIMARY KEY " . ($driver === 'sqlite' ? 'AUTOINCREMENT' : 'AUTO_INCREMENT') . ",
            category_id INTEGER      NOT NULL,
            account_id  INTEGER      DEFAULT 1,
            amount      DECIMAL(15,2) NOT NULL,
            type        VARCHAR(10)  NOT NULL CHECK(type IN ('income','expense')),
            description TEXT,
            date        DATE         NOT NULL,
            created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
            updated_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
            FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE SET NULL
        )";
        $db->exec($sql);

        // Auto-migrasi: tambahkan kolom account_id jika belum ada (DB lama)
        $cols = $db->query("PRAGMA table_info(transactions)")->fetchAll(PDO::FETCH_ASSOC);
        $hasAccount = false;
        foreach ($cols as $col) {
            if ($col['name'] === 'account_id') { $hasAccount = true; break; }
        }
        if (!$hasAccount) {
            $db->exec("ALTER TABLE transactions ADD COLUMN account_id INTEGER DEFAULT 1");
        }

        // Insert data kategori default (hanya jika tabel masih kosong)
        $count = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        if ($count === 0) {
            $defaultCategories = [
                ['Gaji', 'income', '#10B981'],
                ['Penjualan', 'income', '#3B82F6'],
                ['Bonus', 'income', '#8B5CF6'],
                ['Makanan', 'expense', '#EF4444'],
                ['Transportasi', 'expense', '#F59E0B'],
                ['Belanja', 'expense', '#EC4899'],
                ['Tagihan', 'expense', '#6366F1'],
                ['Lainnya', 'expense', '#6B7280'],
            ];

            $stmt = $db->prepare("INSERT INTO categories (name, type, color) VALUES (?, ?, ?)");
            foreach ($defaultCategories as $cat) {
                $stmt->execute($cat);
            }
        }
    }
}
