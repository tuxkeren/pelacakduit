<?php

namespace PelacakDuit\Controllers;

use PelacakDuit\Models\Transaction;
use PelacakDuit\Models\Category;
use PelacakDuit\Models\Account;

class DashboardController
{
    private Transaction $transactionModel;
    private Category $categoryModel;
    private Account $accountModel;

    public function __construct(\PDO $db)
    {
        $this->transactionModel = new Transaction($db);
        $this->categoryModel = new Category($db);
        $this->accountModel = new Account($db);
    }

    public function index(): array
    {
        $summary = $this->transactionModel->getSummary();
        $incomeBreakdown = $this->transactionModel->getCategoryBreakdown('income', 30);
        $expenseBreakdown = $this->transactionModel->getCategoryBreakdown('expense', 30);
        $recentTransactions = $this->transactionModel->getAll(5, 0);

        // Hitung total saldo dari semua akun (Saldo Awal + Pemasukan - Pengeluaran)
        $accounts = $this->accountModel->getSummary();
        $balance = 0;
        foreach ($accounts as $account) {
            $balance += $account['current_balance'];
        }

        return [
            'summary' => $summary,
            'balance' => $balance,
            'incomeBreakdown' => $incomeBreakdown,
            'expenseBreakdown' => $expenseBreakdown,
            'recentTransactions' => $recentTransactions,
        ];
    }

    public function formatCurrency(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
