<?php
$title = 'Edit Akun';
$currentPage = 'accounts';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-md mx-auto">
    <div class="flex items-center mb-6">
        <a href="/index.php?page=accounts" class="mr-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Edit Akun</h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="/index.php?page=accounts&action=update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['account']['id']) ?>">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akun</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($data['account']['name']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Akun</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="cash" <?= $data['account']['type'] === 'cash' ? 'selected' : '' ?>>💵 Tunai / Cash</option>
                    <option value="bank" <?= $data['account']['type'] === 'bank' ? 'selected' : '' ?>>🏦 Bank</option>
                    <option value="ewallet" <?= $data['account']['type'] === 'ewallet' ? 'selected' : '' ?>>📱 E-Wallet</option>
                    <option value="other" <?= $data['account']['type'] === 'other' ? 'selected' : '' ?>>📦 Lainnya</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Awal</label>
                <input type="number" name="balance" step="0.01" min="0" value="<?= htmlspecialchars($data['account']['balance']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
                    Simpan Perubahan
                </button>
                <a href="/index.php?page=accounts" class="flex-1 py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>