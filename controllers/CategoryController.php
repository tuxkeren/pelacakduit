<?php

namespace PelacakDuit\Controllers;

use PelacakDuit\Models\Category;

class CategoryController
{
    private Category $categoryModel;

    public function __construct(\PDO $db)
    {
        $this->categoryModel = new Category($db);
    }

    public function index(): array
    {
        $categories = $this->categoryModel->getAll();
        
        $incomeCategories = array_filter($categories, fn($c) => $c['type'] === 'income');
        $expenseCategories = array_filter($categories, fn($c) => $c['type'] === 'expense');

        return [
            'categories' => $categories,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
        ];
    }

    public function create(): array
    {
        return ['categories' => []];
    }

    public function store(): array
    {
        $name = trim($_POST['name'] ?? '');
        $color = $_POST['color'] ?? '#3B82F6';
        $type = $_POST['type'] ?? 'expense';

        if (empty($name)) {
            return ['success' => false, 'error' => 'Nama kategori wajib diisi'];
        }

        try {
            $id = $this->categoryModel->create($name, $type, $color);
            return ['success' => true, 'id' => $id];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function edit(int $id): array
    {
        $categories = $this->categoryModel->getAll();
        $category = null;

        foreach ($categories as $cat) {
            if ($cat['id'] == $id) {
                $category = $cat;
                break;
            }
        }

        if (!$category) {
            header("Location: /pelacakduit/public/?page=categories");
            exit;
        }

        return ['category' => $category];
    }

    public function update(): void
    {
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $color = $_POST['color'] ?? '#3B82F6';
        $type = $_POST['type'] ?? 'expense';

        if (empty($id) || empty($name)) {
            header("Location: /pelacakduit/public/?page=categories");
            exit;
        }

        $this->categoryModel->update($id, $name, $type, $color);
    }

    public function delete(int $id): array
    {
        try {
            $this->categoryModel->delete($id);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Tidak bisa menghapus kategori yang masih memiliki transaksi'];
        }
    }
}
