<?php
session_start();

require_once 'vendor/autoload.php';

use User\Block\Services\DatabaseService;
use User\Block\Services\Router;
use User\Block\Controllers\TodoController;
use User\Block\Controllers\UserController;
use User\Block\Repositories\TodoRepository;
use User\Block\Repositories\UserRepository;

// Инициализация сервисов
$databaseService = new DatabaseService();
$todoRepository = new TodoRepository($databaseService);
$userRepository = new UserRepository($databaseService);

// Контроллеры
$todoController = new TodoController($todoRepository);
$userController = new UserController($userRepository);

// Инициализация роутера
$router = new Router();

// Регистрация маршрутов через контроллеры
foreach ([$todoController, $userController] as $controller) {
    if ($controller instanceof \User\Block\Interfaces\RouteConfigurable) {
        $controller->registerRoutes($router);
    }
}

// Обработка запроса
$action = $_GET['action'] ?? 'login';
$router->dispatch($_SERVER['REQUEST_METHOD'], "/$action");
?>