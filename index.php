<?php
session_start();

require_once 'vendor/autoload.php';

use User\Block\Services\DatabaseService;
use User\Block\Controllers\TodoController;
use User\Block\Controllers\UserController;
use User\Block\Repositories\TodoRepository;
use User\Block\Repositories\UserRepository;

$databaseService = new DatabaseService();

// Подключение к базе данных
$todoRepository = new TodoRepository($databaseService); // TodoRepository будет работать с SQLite через DatabaseService
$userRepository = new UserRepository($databaseService); // UserRepository аналогично

// Создание контроллеров
$todoController = new TodoController($todoRepository);
$userController = new UserController($userRepository);

// Определение маршрутов
$action = $_GET['action'] ?? 'login';

// Обработка POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'create':
            // Создание новой задачи
            if (!empty($_POST['title']) && isset($_SESSION['user_id'])) {
                $todoController->store($_POST['title']);
            }
            break;
        case 'update':
            // Обновление задачи
            if (isset($_GET['id']) && !empty($_POST['title'])) {
                $todoController->update($_GET['id'], $_POST['title']);
            }
            break;
        case 'delete':
            // Удаление задачи
            if (isset($_GET['id'])) {
                $todoController->destroy($_GET['id']);
            }
            break;
        case 'toggle';
            // Переключение статуса задачи
            if (isset($_GET['id'])) {
                $todoController->toggle($_GET['id']);
            }
        break;
        case 'login':
            // Обработка входа
            $userController->login();
            break;
        case 'register':
            // Обработка регистрации
            $userController->register();
            break;
    }
} else {
    // Обработка GET-запросов
    switch ($action) {
        case 'todos':
            // Показ задач, если пользователь авторизован
            if (isset($_SESSION['user_id'])) {
                $todoController->index();
            } else {
                header('Location: index.php?action=login');
                exit();
            }
            break;
        case 'login':
            // Страница логина
            $userController->login();
            break;
        case 'register':
            // Страница регистрации
            $userController->register();
            break;
        case 'logout':
            // Выход из системы
            $userController->logout();
            break;
        default:
            // Редирект на страницу логина по умолчанию
            header('Location: index.php?action=login');
            exit();
    }
}