<?php
$title = 'Dashboard';
$currentPage = 'dashboard';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pemasukan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Pemasukan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatRupiah($data['summary']['income']) ?></p>
                <p class="text-xs text-gray-500 mt-1">30 hari terakhir</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatRupiah($data['summary']['expense']) ?></p>
                <p class="text-xs text-gray-500 mt-1">30 hari terakhir</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Saldo -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Saldo</p>
                <p class="text-2xl font-bold <?= $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                    <?= formatRupiah($data['balance']) ?>
                </p>
                <p class="text-xs text-gray-500 mt-1">Selisih 30 hari</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Breakdown Pemasukan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pemasukan per Kategori</h3>
        <?php if (empty($data['incomeBreakdown'])): ?>
            <p class="text-gray-500 text-sm">Belum ada data pemasukan</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($data['incomeBreakdown'] as $cat): ?>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= htmlspecialchars($cat['color']) ?>"></span>
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                            <span class="text-sm font-semibold text-gray-900"><?= formatRupiah($cat['total']) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <?php $percentage = ($cat['total'] / $data['summary']['income']) * 100; ?>
                            <div class="h-2 rounded-full" style="width: <?= $percentage ?>%; background-color: <?= htmlspecialchars($cat['color']) ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Breakdown Pengeluaran -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengeluaran per Kategori</h3>
        <?php if (empty($data['expenseBreakdown'])): ?>
            <p class="text-gray-500 text-sm">Belum ada data pengeluaran</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($data['expenseBreakdown'] as $cat): ?>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= htmlspecialchars($cat['color']) ?>"></span>
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                            <span class="text-sm font-semibold text-gray-900"><?= formatRupiah($cat['total']) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <?php $percentage = ($cat['total'] / $data['summary']['expense']) * 100; ?>
                            <div class="h-2 rounded-full" style="width: <?= $percentage ?>%; background-color: <?= htmlspecialchars($cat['color']) ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
        <a href="/index.php?page=transactions" class="text-sm text-primary hover:text-blue-700 font-medium">
            Lihat Semua →
        </a>
    </div>
    <div class="divide-y divide-gray-200">
        <?php if (empty($data['recentTransactions'])): ?>
            <div class="px-6 py-8 text-center">
                <p class="text-gray-500">Belum ada transaksi</p>
                <a href="/index.php?page=transactions&action=create" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                    Tambah Transaksi Pertama
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($data['recentTransactions'] as $trans): ?>
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?= htmlspecialchars($trans['color']) ?>"></span>
                                <span class="font-medium text-gray-900"><?= htmlspecialchars($trans['category_name']) ?></span>
                                <span class="px-2 py-0.5 text-xs rounded-full <?= $trans['type'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $trans['type'] === 'income' ? 'Masuk' : 'Keluar' ?>
                                </span>
                            </div>
                            <?php if ($trans['description']): ?>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($trans['description']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1"><?= date('d/m/Y', strtotime($trans['date'])) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold <?= $trans['type'] === 'income' ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $trans['type'] === 'income' ? '+' : '-' ?> <?= formatRupiah($trans['amount']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
