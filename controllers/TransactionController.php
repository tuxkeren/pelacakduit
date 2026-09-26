<?php

namespace PelacakDuit\Controllers;

use PelacakDuit\Models\Transaction;
use PelacakDuit\Models\Category;
use PelacakDuit\Models\Account;

class TransactionController
{
    private Transaction $transactionModel;
    private Category $categoryModel;
    private Account $accountModel;
    private array $config;

    public function __construct(\PDO $db, array $config)
    {
        $this->transactionModel = new Transaction($db);
        $this->categoryModel = new Category($db);
        $this->accountModel = new Account($db);
        $this->config = $config;
    }

    public function index(): array
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = $this->config['pagination']['per_page'];
        $offset = ($page - 1) * $perPage;

        $transactions = $this->transactionModel->getAll($perPage, $offset);
        $totalCount = $this->transactionModel->getCount();
        $totalPages = ceil($totalCount / $perPage);

        return [
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
        ];
    }

    public function create(): array
    {
        $categories = $this->categoryModel->getAll();
        $accounts = $this->accountModel->getAll();
        return [
            'categories' => $categories,
            'accounts' => $accounts,
            'isEdit' => false,
            'transaction' => null,
        ];
    }

    public function edit(int $id): array
    {
        $transaction = $this->transactionModel->getById($id);
        $categories = $this->categoryModel->getAll();
        $accounts = $this->accountModel->getAll();
        
        return [
            'transaction' => $transaction,
            'categories' => $categories,
            'accounts' => $accounts,
            'isEdit' => true,
        ];
    }

    public function store(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $accountId = (int)($_POST['account_id'] ?? 1);
        $amount = (float)($_POST['amount'] ?? 0);
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? null;
        $date = $_POST['date'] ?? date('Y-m-d');

        if (!$categoryId || !$amount || !$type) {
            return ['error' => 'Data tidak lengkap'];
        }

        $id = $this->transactionModel->create($categoryId, $amount, $type, $description, $date, $accountId);

        return ['success' => true, 'id' => $id];
    }

    public function update(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }

        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $accountId = (int)($_POST['account_id'] ?? 1);
        $amount = (float)($_POST['amount'] ?? 0);
        $description = $_POST['description'] ?? null;
        $date = $_POST['date'] ?? date('Y-m-d');

        if (!$id || !$categoryId || !$amount) {
            return ['error' => 'Data tidak lengkap'];
        }

        $this->transactionModel->update($id, $categoryId, $amount, $description, $date, $accountId);

        return ['success' => true];
    }

    public function delete(): array
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            return ['error' => 'ID tidak valid'];
        }

        $this->transactionModel->delete($id);

        return ['success' => true];
    }

    public function getByType(string $type): array
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = $this->config['pagination']['per_page'];
        $offset = ($page - 1) * $perPage;

        $transactions = $this->transactionModel->getByType($type, $perPage, $offset);

        return [
            'transactions' => $transactions,
            'type' => $type,
        ];
    }
}
