<?php

namespace User\Block\Controllers;

use User\Block\Interfaces\TodoRepositoryInterface;
use User\Block\Middleware\AuthMiddleware;

class TodoController {
    private TodoRepositoryInterface $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository) {
        $this->todoRepository = $todoRepository;
    }

    public function index(): void {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $todos = $this->todoRepository->getAllByUserId($userId);
        include __DIR__ . '/../Views/index.php';
    }

    public function store($title) {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $this->todoRepository->create(['title' => $title, 'user_id' => $userId]);
        header('Location: index.php?action=todos');
        exit;
    }

    public function update(int $id, string $title = null, ?bool $isCompleted = null): void {
        AuthMiddleware::check();

        $data = [];
        if (!is_null($title)) {
            $data['title'] = $title;
        }
        if (!is_null($isCompleted)) {
            $data['is_completed'] = $isCompleted;
        }

        if (!empty($data)) {
            $this->todoRepository->update($id, $data);
        }

        header('Location: index.php?action=todos');
        exit;
    }

    public function toggle($id) {
        AuthMiddleware::check();

        $todo = $this->todoRepository->getById($id);
        if ($todo) {
            $isCompleted = $todo['is_completed'] ? 0 : 1;
            $this->todoRepository->update($id, ['is_completed' => $isCompleted]);
        }

        header('Location: index.php?action=todos');
        exit;
    }

    public function destroy($id) {
        AuthMiddleware::check();
        $this->todoRepository->delete($id);
        header('Location: index.php?action=todos');
        exit;
    }
}
?>
