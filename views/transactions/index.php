<?php
$title = 'Daftar Transaksi';
$currentPage = 'transactions';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Daftar Transaksi</h2>
    <a href="/index.php?page=transactions&action=create" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
        + Tambah Transaksi
    </a>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex gap-2">
        <a href="/index.php?page=transactions" class="px-4 py-2 rounded-lg <?= !isset($_GET['filter']) ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua
        </a>
        <a href="/index.php?page=transactions&filter=income" class="px-4 py-2 rounded-lg <?= ($_GET['filter'] ?? '') === 'income' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pemasukan
        </a>
        <a href="/index.php?page=transactions&filter=expense" class="px-4 py-2 rounded-lg <?= ($_GET['filter'] ?? '') === 'expense' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pengeluaran
        </a>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <?php if (empty($data['transactions'])): ?>
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi pertama Anda.</p>
            <div class="mt-6">
                <a href="/index.php?page=transactions&action=create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700">
                    + Tambah Transaksi
                </a>
            </div>
        </div>
    <?php else: ?>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['transactions'] as $trans): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= date('d/m/Y', strtotime($trans['date'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= htmlspecialchars($trans['color']) ?>"></span>
                                <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($trans['category_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?= htmlspecialchars($trans['account_name'] ?? 'Tunai') ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= htmlspecialchars($trans['description'] ?? '-') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?= $trans['type'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $trans['type'] === 'income' ? 'Masuk' : 'Keluar' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold <?= $trans['type'] === 'income' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $trans['type'] === 'income' ? '+' : '-' ?> <?= formatRupiah($trans['amount']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="/index.php?page=transactions&action=edit&id=<?= $trans['id'] ?>" class="text-primary hover:text-blue-900 mr-3">Edit</a>
                            <a href="/index.php?page=transactions&action=delete&id=<?= $trans['id'] ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini?')" class="text-red-600 hover:text-red-900">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($data['totalPages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($data['currentPage'] > 1): ?>
                        <a href="?page=transactions&p=<?= $data['currentPage'] - 1 ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Sebelumnya
                        </a>
                    <?php endif; ?>
                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                        <a href="?page=transactions&p=<?= $data['currentPage'] + 1 ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Selanjutnya
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Halaman <span class="font-medium"><?= $data['currentPage'] ?></span> dari <span class="font-medium"><?= $data['totalPages'] ?></span>
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                <a href="?page=transactions&p=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $data['currentPage'] ? 'text-primary bg-blue-50 border-primary' : 'text-gray-700 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </nav>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
