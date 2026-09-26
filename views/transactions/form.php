<?php
$title = $data['isEdit'] ? 'Edit Transaksi' : 'Tambah Transaksi';
$currentPage = 'transactions';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="/index.php?page=transactions" class="mr-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900"><?= $title ?></h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="/index.php?page=transactions&action=<?= $data['isEdit'] ? 'update' : 'store' ?>">
            <?php if ($data['isEdit']): ?>
                <input type="hidden" name="id" value="<?= $data['transaction']['id'] ?>">
            <?php endif; ?>

            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                <div class="flex gap-4">
                    <label class="flex-1">
                        <input type="radio" name="type" value="income" class="sr-only peer" 
                            <?= (!$data['isEdit'] || $data['transaction']['type'] === 'income') ? 'checked' : '' ?>
                            onchange="updateCategories('income')">
                        <div class="cursor-pointer text-center py-3 px-4 rounded-lg border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 font-medium transition-all">
                            Pemasukan
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="type" value="expense" class="sr-only peer"
                            <?= $data['isEdit'] && $data['transaction']['type'] === 'expense' ? 'checked' : '' ?>
                            onchange="updateCategories('expense')">
                        <div class="cursor-pointer text-center py-3 px-4 rounded-lg border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 font-medium transition-all">
                            Pengeluaran
                        </div>
                    </label>
                </div>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($data['categories'] as $cat): ?>
                        <option value="<?= $cat['id'] ?>" data-type="<?= $cat['type'] ?>" data-color="<?= $cat['color'] ?>"
                            <?= $data['isEdit'] && $data['transaction']['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Account / Akun -->
            <div class="mb-4">
                <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">Akun / Dompet</label>
                <select name="account_id" id="account_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Akun</option>
                    <?php foreach ($data['accounts'] as $acc): ?>
                        <option value="<?= $acc['id'] ?>"
                            <?= $data['isEdit'] && $data['transaction']['account_id'] == $acc['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($acc['name']) ?> (<?= ucfirst($acc['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Amount -->
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" id="amount" required step="0.01" min="0"
                    value="<?= $data['isEdit'] ? $data['transaction']['amount'] : '' ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Contoh: 50000">
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="date" id="date" required
                    value="<?= $data['isEdit'] ? $data['transaction']['date'] : date('Y-m-d') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Tambahkan catatan..."><?= $data['isEdit'] ? htmlspecialchars($data['transaction']['description'] ?? '') : '' ?></textarea>
            </div>

            <!-- Submit -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
                    <?= $data['isEdit'] ? 'Simpan Perubahan' : 'Tambah Transaksi' ?>
                </button>
                <a href="/index.php?page=transactions" class="py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const categories = <?= json_encode($data['categories']) ?>;
const currentType = '<?= $data['isEdit'] ? $data['transaction']['type'] : 'income' ?>';

function updateCategories(type) {
    const select = document.getElementById('category_id');
    const currentValue = select.value;
    
    // Clear options except the first placeholder
    while (select.options.length > 1) {
        select.remove(1);
    }
    
    // Add filtered options
    categories.forEach(cat => {
        if (cat.type === type) {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            option.dataset.type = cat.type;
            option.dataset.color = cat.color;
            select.appendChild(option);
        }
    });
    
    // Reset selection if current value not in filtered list
    if (!Array.from(select.options).some(o => o.value === currentValue)) {
        select.value = '';
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateCategories(currentType);
    <?php if ($data['isEdit']): ?>
        document.getElementById('category_id').value = '<?= $data['transaction']['category_id'] ?>';
    <?php endif; ?>
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
