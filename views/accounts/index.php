<?php
$title = 'Manajemen Akun / Dompet';
$currentPage = 'accounts';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Akun / Dompet</h2>
        <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
            + Tambah Akun
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php 
        $totalIncome = 0;
        $totalExpense = 0;
        foreach ($data['accounts'] as $acc):
            $totalIncome += $acc['total_income'];
            $totalExpense += $acc['total_expense'];
        endforeach;
        ?>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <p class="text-sm text-gray-600 font-medium">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600 mt-1"><?= formatRupiah($totalIncome) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <p class="text-sm text-gray-600 font-medium">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-red-600 mt-1"><?= formatRupiah($totalExpense) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <p class="text-sm text-gray-600 font-medium">Saldo Bersih</p>
            <p class="text-2xl font-bold <?= $totalIncome - $totalExpense >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                <?= formatRupiah($totalIncome - $totalExpense) ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <p class="text-sm text-gray-600 font-medium">Jumlah Akun</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= count($data['accounts']) ?></p>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Saldo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($data['accounts'])): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada akun</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan akun/dompet pertama Anda.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['accounts'] as $acc): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: <?= 
                                        $acc['type'] === 'cash' ? '#10B981' : 
                                        ($acc['type'] === 'bank' ? '#3B82F6' : 
                                        ($acc['type'] === 'ewallet' ? '#8B5CF6' : '#6B7280'))
                                    ?>"></span>
                                    <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($acc['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    <?= 
                                        $acc['type'] === 'cash' ? 'bg-green-100 text-green-800' : 
                                        ($acc['type'] === 'bank' ? 'bg-blue-100 text-blue-800' : 
                                        ($acc['type'] === 'ewallet' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))
                                    ?>">
                                    <?= ucfirst($acc['type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                                <?= formatRupiah($acc['initial_balance']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600">
                                + <?= formatRupiah($acc['total_income']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-red-600">
                                - <?= formatRupiah($acc['total_expense']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold 
                                <?= $acc['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $acc['current_balance'] >= 0 ? '+' : '' ?><?= formatRupiah($acc['current_balance']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/index.php?page=accounts&action=edit&id=<?= $acc['id'] ?>" class="text-primary hover:text-blue-900 mr-3">Edit</a>
                                <a href="/index.php?page=accounts&action=delete&id=<?= $acc['id'] ?>" onclick="return confirm('Yakin ingin menghapus akun ini? Transaksi akan tetap ada tapi tanpa akun.')" class="text-red-600 hover:text-red-900">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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