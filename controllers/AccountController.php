<?php

namespace PelacakDuit\Controllers;

use PelacakDuit\Models\Account;

class AccountController
{
    private Account $accountModel;

    public function __construct(\PDO $db)
    {
        $this->accountModel = new Account($db);
    }

    public function index(): array
    {
        $accounts = $this->accountModel->getSummary();
        return [
            'accounts' => $accounts,
        ];
    }

    public function store(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }

        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? 'bank';
        $balance = (float)($_POST['balance'] ?? 0);

        if (!$name) {
            return ['error' => 'Nama akun diperlukan'];
        }

        $id = $this->accountModel->create($name, $type, $balance);
        return ['success' => true, 'id' => $id];
    }

    public function edit(int $id): array
    {
        $account = $this->accountModel->getById($id);
        return [
            'account' => $account,
            'isEdit' => true,
        ];
    }

    public function update(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? 'bank';
        $balance = (float)($_POST['balance'] ?? 0);

        if (!$id || !$name) {
            return ['error' => 'Data tidak lengkap'];
        }

        $this->accountModel->update($id, $name, $type, $balance);
        return ['success' => true];
    }

    public function delete(): array
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            return ['error' => 'ID tidak valid'];
        }

        $this->accountModel->delete($id);
        return ['success' => true];
    }
}
