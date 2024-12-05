<?php

namespace User\Block\Controllers;


use User\Block\Interfaces\RouteConfigurable;
use User\Block\Services\Router;
use User\Block\Interfaces\TodoRepositoryInterface;
use User\Block\Middleware\AuthMiddleware;

class TodoController implements RouteConfigurable
{
    private TodoRepositoryInterface $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function registerRoutes(Router $router): void
    {
        $router->addRoute('GET', '/todos', [$this, 'index']);
        $router->addRoute('POST', '/create', [$this, 'store']);
        $router->addRoute('POST', '/update', [$this, 'update']);
        $router->addRoute('POST', '/delete', [$this, 'destroy']);
        $router->addRoute('POST', '/toggle', [$this, 'toggle']);
    }

    public function index(): void
    {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $todos = $this->todoRepository->getAllByUserId($userId);
        include __DIR__ . '/../Views/index.php';
    }

    public function store(string $title): void
    {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $this->todoRepository->create(['title' => $title, 'user_id' => $userId]);
        header('Location: index.php?action=todos');
        exit;
    }

    public function update(int $id, string $title): void
    {
        AuthMiddleware::check();
        $this->todoRepository->update($id, ['title' => $title]);
        header('Location: index.php?action=todos');
        exit;
    }

    public function toggle(int $id): void
    {
        AuthMiddleware::check();
        $todo = $this->todoRepository->getById($id);
        if ($todo) {
            $isCompleted = $todo['is_completed'] ? 0 : 1;
            $this->todoRepository->update($id, ['is_completed' => $isCompleted]);
        }
        header('Location: index.php?action=todos');
        exit;
    }

    public function destroy(int $id): void
    {
        AuthMiddleware::check();
        $this->todoRepository->delete($id);
        header('Location: index.php?action=todos');
        exit;
    }
}
?>