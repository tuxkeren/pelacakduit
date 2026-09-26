# PelacakDuit - Aplikasi Pencatatan Keuangan Sederhana

Aplikasi CRUD sederhana untuk pencatatan **uang masuk (pemasukan)** dan **uang keluar (pengeluaran)** menggunakan:
- **PHP 8+** dengan **OOP** (Object-Oriented Programming)
- **TailwindCSS** via CDN (tanpa build step)
- **SQLite** (default, jalan langsung) / **MariaDB/MySQL** (production)

## ⚠️ Disclaimer

**Program ini dibuat untuk tujuan belajar PHP OOP dan TIDAK memiliki fitur autentikasi/login.**

- ❌ Tidak ada sistem login/user management
- ❌ Tidak ada session/cookies untuk autentikasi
- ❌ Semua data dapat diakses siapa saja yang membuka URL
- ✅ Cocok untuk belajar konsep MVC, OOP, dan CRUD
- ✅ Untuk pembelajaran dan development lokal

**Jangan gunakan untuk data sensitif atau production tanpa menambahkan layer autentikasi terlebih dahulu!**

## 🚀 Cara Menjalankan (Development)

### Opsi 1: PHP Built-in Server + SQLite (Langsung jalan)
```bash
cd /home/administrator/pelacakduit/public
php -S localhost:8000
```
Akses: http://localhost:8000

### Opsi 2: Dengan MariaDB/MySQL
1. Buat database: `CREATE DATABASE pelacakduit;`
2. Edit `config/config.php`:
   ```php
   'driver' => 'mysql',
   'host'   => 'localhost',
   'dbname' => 'pelacakduit',
   'username' => 'root',
   'password' => 'password_anda',
   ```
3. Jalankan server PHP seperti di atas.

## 📁 Struktur Project

```
pelacakduit/
├── config/
│   ├── config.php          # Konfigurasi aplikasi & database
│   ├── Database.php        # Kelas Database (Singleton PDO)
│   └── Schema.php          # Auto-create tables
├── controllers/
│   ├── DashboardController.php
│   ├── TransactionController.php
│   └── CategoryController.php
├── models/
│   ├── Transaction.php     # Model transaksi (CRUD + statistik)
│   └── Category.php        # Model kategori
├── views/
│   ├── layouts/
│   │   ├── header.php      # Header + Tailwind CDN + Navigation
│   │   └── footer.php
│   ├── dashboard/
│   │   └── index.php       # Dashboard dengan ringkasan & chart sederhana
│   ├── transactions/
│   │   ├── index.php       # List transaksi + pagination + filter
│   │   └── form.php        # Form tambah/edit (AJAX category filter)
│   └── categories/
│       ├── index.php       # Manajemen kategori (modal + fetch API)
│       └── edit.php
├── public/
│   ├── index.php           # Entry point + router sederhana
│   └── .htaccess           # Apache rewrite rules
└── database/
    └── pelacakduit.sqlite  # SQLite database (auto-created)
```

## ✨ Fitur

### Dashboard
- Ringkasan: Total Pemasukan, Total Pengeluaran, Saldo (30 hari)
- Breakdown per kategori dengan progress bar
- 10 transaksi terbaru

### Transaksi
- CRUD lengkap: Tambah, Edit, Hapus
- Filter: Semua / Pemasukan / Pengeluaran
- Pagination (10 per halaman)
- Validasi form (client & server side)

### Kategori
- CRUD via modal (AJAX)
- Warna custom per kategori (color picker)
- Tipe: Pemasukan / Pengeluaran
- Validasi tidak bisa hapus kalau masih dipakai transaksi

## 🎨 UI/UX
- **TailwindCSS** via CDN (no build)
- Responsive: mobile-first
- Warna brand: Biru (#3B82F6) untuk utama, Hijau untuk income, Merah untuk expense
- Icon SVG inline (tidak perlu font icons)

## 🔧 Konfigurasi Database

### SQLite (Default - Development)
```php
'driver' => 'sqlite',
'path'   => BASE_PATH . '/database/pelacakduit.sqlite',
```
File database otomatis dibuat di folder `database/`.

### MariaDB/MySQL (Production)
```php
'driver' => 'mysql',
'host'   => '127.0.0.1',
'port'   => 3306,
'dbname' => 'pelacakduit',
'username' => 'pelacakduit_user',
'password' => 'password_kuat',
'charset'  => 'utf8mb4',
```

## 📋 Tabel Database

### `categories`
| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto increment |
| name | VARCHAR(100) | Nama kategori |
| type | ENUM('income','expense') | Tipe kategori |
| color | VARCHAR(7) | Hex color (#RRGGBB) |
| created_at | DATETIME | |

### `transactions`
| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto increment |
| category_id | INTEGER | FK ke categories.id |
| type | ENUM('income','expense') | Tipe transaksi |
| amount | DECIMAL(15,2) | Jumlah uang |
| date | DATE | Tanggal transaksi |
| description | TEXT | Catatan (nullable) |
| created_at | DATETIME | |

## 🛡️ Keamanan
- Prepared statements (PDO) → prevent SQL injection
- `htmlspecialchars()` di semua output → prevent XSS
- CSRF protection (bisa ditambah token di form)
- Input validation server-side

## 📝 Catatan Deploy ke Production
1. Ganti `APP_DEBUG = false` di config
2. Gunakan MariaDB/MySQL (bukan SQLite)
3. Set `display_errors = 0` di php.ini
4. Gunakan HTTPS
5. Tambahkan authentication (login) jika multi-user

## 📄 Lisensi
MIT License - Bebas digunakan & dimodifikasi.