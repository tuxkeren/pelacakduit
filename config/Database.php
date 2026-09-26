<?php

namespace PelacakDuit\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(?array $config = null): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = $config ?? require __DIR__ . '/config.php';
        $dbConfig = $config['database'];

        try {
            if ($dbConfig['driver'] === 'sqlite') {
                $dsn = 'sqlite:' . $dbConfig['sqlite']['path'];
                self::$instance = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } else {
                $c = $dbConfig['mysql'];
                $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
                self::$instance = new PDO($dsn, $c['username'], $c['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            }

            return self::$instance;
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}
