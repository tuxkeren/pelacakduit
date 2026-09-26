<?php
/**
 * PelacakDuit - Configuration
 * Pencatatan uang masuk & keluar sederhana dengan PHP OOP + TailwindCSS
 */

return [
    'app' => [
        'name'    => 'PelacakDuit',
        'version' => '1.0.0',
        'base_url' => 'http://localhost:8000',
        'path_prefix' => '/AI/pelacakduit/public',
    ],

    // ============================================
    // DATABASE - Pilih salah satu
    // ============================================
    // Untuk development lokal (langsung jalan tanpa install DB):
    'database' => [
        'driver'   => 'sqlite',          // 'sqlite' | 'mysql'
        'sqlite'   => [
            'path' => __DIR__ . '/../database/pelacakduit.db',
        ],
        'mysql'   => [
            'host'     => '127.0.0.1',
            'port'     => 3306,
            'database' => 'pelacakduit',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4',
        ],
    ],

    'currency' => [
        'symbol' => 'Rp',
        'code'   => 'IDR',
    ],

    'pagination' => [
        'per_page' => 10,
    ],
];