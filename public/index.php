<?php
/**
 * PelacakDuit - Main Entry Point
 * Pencatatan uang masuk & uang keluar sederhana
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load config
$config = require BASE_PATH . '/config/config.php';

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'PelacakDuit\\';
    $baseDir = BASE_PATH . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    // Map namespace to lowercase folder names
    $relativeClass = str_replace(['Config\\', 'Models\\', 'Controllers\\'], ['config/', 'models/', 'controllers/'], $relativeClass);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize database
use PelacakDuit\Config\Database;
use PelacakDuit\Config\Schema;

$db = Database::getConnection($config);
$driver = $config['database']['driver'];

// Create tables if they don't exist
Schema::createTables($db, $driver);

// Route simple
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Base URL (server runs from inside /public, so root = /)
$baseUrl = '';

// Dispatch
switch ($page) {
    case 'dashboard':
        require BASE_PATH . '/controllers/DashboardController.php';
        $controller = new PelacakDuit\Controllers\DashboardController($db);
        $data = $controller->index();
        require BASE_PATH . '/views/dashboard/index.php';
        break;

    case 'transactions':
        require BASE_PATH . '/controllers/TransactionController.php';
        $controller = new PelacakDuit\Controllers\TransactionController($db, $config);
        
        switch ($action) {
            case 'create':
                $data = $controller->create();
                require BASE_PATH . '/views/transactions/form.php';
                break;
            case 'store':
                $controller->store();
                header("Location: {$baseUrl}/?page=transactions");
                exit;
            case 'edit':
                $data = $controller->edit($id);
                require BASE_PATH . '/views/transactions/form.php';
                break;
            case 'update':
                $controller->update();
                header("Location: {$baseUrl}/?page=transactions");
                exit;
            case 'delete':
                $controller->delete($id);
                header("Location: {$baseUrl}/?page=transactions");
                exit;
            default:
                $data = $controller->index();
                require BASE_PATH . '/views/transactions/index.php';
                break;
        }
        break;

    case 'categories':
        require BASE_PATH . '/controllers/CategoryController.php';
        $controller = new PelacakDuit\Controllers\CategoryController($db);
        
        switch ($action) {
            case 'edit':
                $data = $controller->edit($id);
                require BASE_PATH . '/views/categories/edit.php';
                break;
            case 'update':
                $controller->update();
                header("Location: {$baseUrl}/?page=categories");
                exit;
            case 'store':
                $result = $controller->store();
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            case 'delete':
                $result = $controller->delete($id);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            default:
                $data = $controller->index();
                require BASE_PATH . '/views/categories/index.php';
                break;
        }
        break;

    case 'accounts':
        require BASE_PATH . '/controllers/AccountController.php';
        $controller = new PelacakDuit\Controllers\AccountController($db);

        switch ($action) {
            case 'edit':
                $data = $controller->edit($id);
                require BASE_PATH . '/views/accounts/edit.php';
                break;
            case 'update':
                $result = $controller->update();
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    exit;
                }
                header("Location: {$baseUrl}/?page=accounts");
                exit;
            case 'store':
                $result = $controller->store();
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            case 'delete':
                $result = $controller->delete($id);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            default:
                $data = $controller->index();
                require BASE_PATH . '/views/accounts/index.php';
                break;
        }
        break;

    default:
        echo "Halaman tidak ditemukan.";
        break;
}
