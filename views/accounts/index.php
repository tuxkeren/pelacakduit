<?php
$title = 'Manajemen Akun / Dompet';
$currentPage = 'accounts';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

$totalIncome = 0;
$totalExpense = 0;
$totalBalance = 0;
foreach ($data['accounts'] as $acc):
    $totalIncome += $acc['total_income'];
    $totalExpense += $acc['total_expense'];
    $totalBalance += $acc['current_balance'];
endforeach;
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Manajemen Akun / Dompet</h2>
    <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
        + Tambah Akun
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pemasukan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Pemasukan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatRupiah($totalIncome) ?></p>
                <p class="text-xs text-gray-500 mt-1">Dari semua akun</p>
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
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatRupiah($totalExpense) ?></p>
                <p class="text-xs text-gray-500 mt-1">Dari semua akun</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Saldo -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Saldo</p>
                <p class="text-2xl font-bold <?= $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                    <?= formatRupiah($totalBalance) ?>
                </p>
                <p class="text-xs text-gray-500 mt-1">Gabungan semua akun</p>
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
    <!-- Pemasukan per Akun -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pemasukan per Akun</h3>
        <?php if (empty($data['accounts']) || $totalIncome == 0): ?>
            <p class="text-gray-500 text-sm">Belum ada pemasukan</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($data['accounts'] as $acc): ?>
                    <?php if ($acc['total_income'] <= 0) continue; ?>
                    <?php $percentage = $totalIncome > 0 ? ($acc['total_income'] / $totalIncome) * 100 : 0; ?>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= $acc['type'] === 'cash' ? '#10B981' : ($acc['type'] === 'bank' ? '#3B82F6' : ($acc['type'] === 'ewallet' ? '#8B5CF6' : '#6B7280')) ?>"></span>
                                <?= htmlspecialchars($acc['name']) ?>
                            </span>
                            <span class="text-sm font-semibold text-gray-900"><?= formatRupiah($acc['total_income']) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pengeluaran per Akun -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengeluaran per Akun</h3>
        <?php if (empty($data['accounts']) || $totalExpense == 0): ?>
            <p class="text-gray-500 text-sm">Belum ada pengeluaran</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($data['accounts'] as $acc): ?>
                    <?php if ($acc['total_expense'] <= 0) continue; ?>
                    <?php $percentage = $totalExpense > 0 ? ($acc['total_expense'] / $totalExpense) * 100 : 0; ?>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= $acc['type'] === 'cash' ? '#10B981' : ($acc['type'] === 'bank' ? '#3B82F6' : ($acc['type'] === 'ewallet' ? '#8B5CF6' : '#6B7280')) ?>"></span>
                                <?= htmlspecialchars($acc['name']) ?>
                            </span>
                            <span class="text-sm font-semibold text-gray-900"><?= formatRupiah($acc['total_expense']) ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Daftar Akun -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Akun</h3>
        <span class="text-sm text-gray-500"><?= count($data['accounts']) ?> akun aktif</span>
    </div>
    <div class="divide-y divide-gray-200">
        <?php if (empty($data['accounts'])): ?>
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada akun</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan akun/dompet pertama Anda.</p>
                <button onclick="openModal()" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                    Tambah Akun Pertama
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($data['accounts'] as $acc): ?>
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?= $acc['type'] === 'cash' ? '#10B981' : ($acc['type'] === 'bank' ? '#3B82F6' : ($acc['type'] === 'ewallet' ? '#8B5CF6' : '#6B7280')) ?>"></span>
                                <span class="font-medium text-gray-900"><?= htmlspecialchars($acc['name']) ?></span>
                                <span class="px-2 py-0.5 text-xs rounded-full 
                                    <?= $acc['type'] === 'cash' ? 'bg-green-100 text-green-800' : ($acc['type'] === 'bank' ? 'bg-blue-100 text-blue-800' : ($acc['type'] === 'ewallet' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($acc['type']) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Saldo awal <?= formatRupiah($acc['initial_balance']) ?> 
                                · Masuk +<?= formatRupiah($acc['total_income']) ?> 
                                · Keluar -<?= formatRupiah($acc['total_expense']) ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold <?= $acc['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $acc['current_balance'] >= 0 ? '+' : '' ?><?= formatRupiah($acc['current_balance']) ?>
                            </p>
                            <div class="flex justify-end gap-3 mt-1">
                                <a href="/index.php?page=accounts&action=edit&id=<?= $acc['id'] ?>" class="text-sm text-primary hover:text-blue-900">Edit</a>
                                <a href="/index.php?page=accounts&action=delete&id=<?= $acc['id'] ?>" onclick="return confirm('Yakin ingin menghapus akun ini? Transaksi akan tetap ada tapi tanpa akun.')" class="text-sm text-red-600 hover:text-red-900">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah/Edit Akun -->
<div id="accountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Tambah Akun</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="accountForm" method="POST" action="/index.php?page=accounts&action=store">
                <input type="hidden" name="id" id="accountId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akun</label>
                    <input type="text" name="name" id="accountName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Contoh: Bank BCA, Dompet Utama">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Akun</label>
                    <select name="type" id="accountType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="cash">💵 Tunai / Cash</option>
                        <option value="bank" selected>🏦 Bank</option>
                        <option value="ewallet">📱 E-Wallet</option>
                        <option value="other">📦 Lainnya</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Awal (Opsional)</label>
                    <input type="number" name="balance" id="accountBalance" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Contoh: 1000000">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(edit = false, data = {}) {
    const modal = document.getElementById('accountModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('accountForm');
    const id = document.getElementById('accountId');
    const name = document.getElementById('accountName');
    const type = document.getElementById('accountType');
    const balance = document.getElementById('accountBalance');

    form.reset();
    id.value = '';
    
    if (edit && data) {
        title.textContent = 'Edit Akun';
        id.value = data.id;
        name.value = data.name;
        type.value = data.type;
        balance.value = data.balance;
        form.action = '/index.php?page=accounts&action=update';
    } else {
        title.textContent = 'Tambah Akun';
        form.action = '/index.php?page=accounts&action=store';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    name.focus();
}

function closeModal() {
    const modal = document.getElementById('accountModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('accountForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        const result = await res.json();
        if (result.success) {
            location.reload();
        } else {
            alert('Error: ' + (result.error || 'Gagal menyimpan'));
        }
    } catch (err) {
        alert('Error: ' + err.message);
    }
});

// Enable edit from query param
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('action') === 'edit' && urlParams.get('id')) {
    const id = urlParams.get('id');
    fetch(`/index.php?page=accounts&action=edit&id=${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.account) {
                openModal(true, data.account);
            }
        });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
