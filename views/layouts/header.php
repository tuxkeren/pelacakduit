<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PelacakDuit' ?> - Pencatatan Keuangan Sederhana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-primary">💰 PelacakDuit</h1>
                </div>
                <div class="flex space-x-4 items-center">
                    <a href="/index.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium <?= ($currentPage ?? '') === 'dashboard' ? 'bg-blue-50 text-primary' : '' ?>">
                        Dashboard
                    </a>
                    <a href="/index.php?page=transactions" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium <?= ($currentPage ?? '') === 'transactions' ? 'bg-blue-50 text-primary' : '' ?>">
                        Transaksi
                    </a>
                    <a href="/index.php?page=categories" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium <?= ($currentPage ?? '') === 'categories' ? 'bg-blue-50 text-primary' : '' ?>">
                        Kategori
                    </a>
                    <a href="/index.php?page=accounts" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium <?= ($currentPage ?? '') === 'accounts' ? 'bg-blue-50 text-primary' : '' ?>">
                        Akun
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($alert)): ?>
            <div class="mb-6 p-4 rounded-lg <?= $alert['type'] === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' ?>">
                <?= htmlspecialchars($alert['message']) ?>
            </div>
        <?php endif; ?>