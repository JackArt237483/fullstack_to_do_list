<?php

namespace User\Block\Controllers;

use User\Block\Interfaces\TodoRepositoryInterface;
use User\Block\Interfaces\RouteConfigurable;
use User\Block\Services\Router;
use User\Block\Middleware\AuthMiddleware;

class TodoController implements RouteConfigurable {
    private TodoRepositoryInterface $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository) {
        $this->todoRepository = $todoRepository;
    }

    public function registerRoutes(Router $router): void {
        $router->addRoute('GET', '/todos', [$this, 'index']);
        $router->addRoute('POST', '/create', [$this, 'store']);
        $router->addRoute('POST', '/update', [$this, 'update']);
        $router->addRoute('POST', '/delete', [$this, 'destroy']);
        $router->addRoute('POST', '/toggle', [$this, 'toggle']);
    }

    public function index(): void {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $todos = $this->todoRepository->getAllByUserId($userId);
        include __DIR__ . '/../Views/index.php';
    }

    public function store(array $params): void {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $title = $params['title'] ?? null;

        if ($title) {
            $this->todoRepository->create(['title' => $title, 'user_id' => $userId]);
        }

        header('Location: index.php?action=todos');
        exit;
    }

    public function update(array $params): void {
        AuthMiddleware::check();

        $id = $params['id'] ?? null;
        $title = $params['title'] ?? null;
        $isCompleted = isset($params['is_completed']) ? (bool)$params['is_completed'] : null;

        if ($id) {
            $data = [];
            if ($title !== null) {
                $data['title'] = $title;
            }
            if ($isCompleted !== null) {
                $data['is_completed'] = $isCompleted;
            }

            $this->todoRepository->update($id, $data);
        }

        header('Location: index.php?action=todos');
        exit;
    }

    public function toggle(array $params): void {
        AuthMiddleware::check();

        $id = $params['id'] ?? null;

        if ($id) {
            $todo = $this->todoRepository->getById($id);
            if ($todo) {
                $isCompleted = $todo['is_completed'] ? 0 : 1;
                $this->todoRepository->update($id, ['is_completed' => $isCompleted]);
            }
        }

        header('Location: index.php?action=todos');
        exit;
    }

    public function destroy(array $params): void {
        AuthMiddleware::check();

        $id = $params['id'] ?? null;

        if ($id) {
            $this->todoRepository->delete($id);
        }

        header('Location: index.php?action=todos');
        exit;
    }
}
?>
