<?php
$title = 'Manajemen Kategori';
$currentPage = 'categories';
require_once __DIR__ . '/../layouts/header.php';

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h2>
    <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
        + Tambah Kategori
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Kategori Pemasukan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
            Kategori Pemasukan
        </h3>
        <?php if (empty($data['incomeCategories'])): ?>
            <p class="text-gray-500 text-sm">Belum ada kategori pemasukan</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <?php foreach ($data['incomeCategories'] as $cat): ?>
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full mr-2" style="background-color: <?= htmlspecialchars($cat['color']) ?>"></span>
                            <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($cat['name']) ?></span>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="editCategory(<?= json_encode($cat) ?>)" class="text-primary hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="deleteCategory(<?= $cat['id'] ?>)" class="text-red-600 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kategori Pengeluaran -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
            Kategori Pengeluaran
        </h3>
        <?php if (empty($data['expenseCategories'])): ?>
            <p class="text-gray-500 text-sm">Belum ada kategori pengeluaran</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <?php foreach ($data['expenseCategories'] as $cat): ?>
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full mr-2" style="background-color: <?= htmlspecialchars($cat['color']) ?>"></span>
                            <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($cat['name']) ?></span>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="editCategory(<?= json_encode($cat) ?>)" class="text-primary hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="deleteCategory(<?= $cat['id'] ?>)" class="text-red-600 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function openModal() {
    const type = prompt('Pilih tipe kategori (income/expense):', 'expense');
    if (type && (type === 'income' || type === 'expense')) {
        document.getElementById('modal-title').textContent = 'Tambah Kategori';
        document.getElementById('cat-id').value = '';
        document.getElementById('cat-name').value = '';
        document.getElementById('cat-color').value = '#3B82F6';
        document.getElementById('cat-type').value = type;
        document.getElementById('categoryModal').classList.remove('hidden');
    }
}

function editCategory(category) {
    document.getElementById('modal-title').textContent = 'Edit Kategori';
    document.getElementById('cat-id').value = category.id;
    document.getElementById('cat-name').value = category.name;
    document.getElementById('cat-color').value = category.color;
    document.getElementById('cat-type').value = category.type;
    document.getElementById('categoryModal').classList.remove('hidden');
}

function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini? Semua transaksi dengan kategori ini akan terhapus.')) {
        fetch(`/index.php?page=categories&action=delete&id=${id}`, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus: ' + (data.error || 'Kesalahan tidak diketahui'));
                }
            });
    }
}

function submitForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    fetch('/index.php?page=categories&action=store', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('categoryModal').classList.add('hidden');
            location.reload();
        } else {
            alert('Gagal menyimpan: ' + (data.error || 'Kesalahan tidak diketahui'));
        }
    });
}
</script>

<!-- Modal -->
<div id="categoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 mb-4">Form Kategori</h3>
        <form id="categoryForm" onsubmit="submitForm(event)">
            <input type="hidden" id="cat-id" name="id" value="">
            <input type="hidden" id="cat-type" name="type" value="expense">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" id="cat-name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="Contoh: Makanan">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                <div class="flex gap-2 flex-wrap">
                    <?php foreach (['#EF4444', '#F59E0B', '#EC4899', '#8B5CF6', '#3B82F6', '#10B981', '#6366F1', '#6B7280'] as $color): ?>
                        <label class="flex items-center">
                            <input type="radio" name="color" value="<?= $color ?>" <?= $color === '#3B82F6' ? 'checked' : '' ?> class="w-4 h-4">
                            <span class="w-6 h-6 rounded-full ml-2" style="background-color: <?= $color ?>"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="mt-2">
                    <label class="block text-sm text-gray-500">Atau pilih warna lain:</label>
                    <input type="color" name="color" id="custom-color" value="#3B82F6" class="mt-2">
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
                    Simpan
                </button>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
