<?php
$title = 'Kategori - Edit';
$currentPage = 'categories';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Kategori</h2>
    
    <form method="POST" action="/index.php?page=categories&action=update" class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <input type="hidden" name="id" value="<?= $data['category']['id'] ?>">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['category']['name']) ?>" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
            <input type="color" name="color" value="<?= htmlspecialchars($data['category']['color']) ?>" class="w-full h-10">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="income" <?= $data['category']['type'] === 'income' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="expense" <?= $data['category']['type'] === 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        
        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-blue-700">
                Simpan
            </button>
            <a href="/index.php?page=categories" class="py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
