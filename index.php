<?php
define('BASE_PATH', dirname(__DIR__) . 'index.php');

require_once 'vendor/autoload.php';

use User\Block\Controllers\TodoController;

// Инициализация контроллера
$controller = new TodoController();

// Определение маршрутов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    if ($action === 'create' && !empty($_POST['title'])) {
        $controller->store($_POST['title']);
    } elseif ($action === 'toggle' && isset($_GET['id'])) {
        $controller->complete($_GET['id']);
    } elseif ($action === 'delete' && isset($_GET['id'])) {
        $controller->destroy($_GET['id']);
    } elseif ($action === 'update' && isset($_GET['id']) && !empty($_POST['title'])) {
        $controller->update($_GET['id'], $_POST['title']);
    }
} else {
    $controller->index();
}
