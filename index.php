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

    // Добавляем маршруты
    // Для задач
    $router->addRoute('GET', '/todos', function() use ($todoController) {
        $todoController->index();
    });

    $router->addRoute('POST', '/create', function() use ($todoController) {
        if (!empty($_POST['title'])) {
            $todoController->store($_POST['title']);
        }
    });

    $router->addRoute('POST', '/update', function() use ($todoController) {
        if (isset($_GET['id']) && !empty($_POST['title'])) {
            $todoController->update($_GET['id'], $_POST['title']);
        }
    });

    $router->addRoute('POST', '/delete', function() use ($todoController) {
        if (isset($_GET['id'])) {
            $todoController->destroy($_GET['id']);
        }
    });

    $router->addRoute('POST', '/toggle', function() use ($todoController) {
        if (isset($_GET['id'])) {
            $todoController->toggle($_GET['id']);
        }
    });

    // Для пользователей
    $router->addRoute('GET', '/login', function() use ($userController) {
        $userController->login();
    });

    $router->addRoute('POST', '/login', function() use ($userController) {
        $userController->login();
    });

    $router->addRoute('GET', '/register', function() use ($userController) {
        $userController->register();
    });

    $router->addRoute('POST', '/register', function() use ($userController) {
        $userController->register();
    });

    $router->addRoute('GET', '/logout', function() use ($userController) {
        $userController->logout();
    });

    // Обработка запроса
    $action = $_GET['action'] ?? 'login';
    $router->dispatch($_SERVER['REQUEST_METHOD'], "/$action");

?>
