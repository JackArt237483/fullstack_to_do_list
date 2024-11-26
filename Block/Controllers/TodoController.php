<?php

namespace User\Block\Controllers;

use User\Block\Models\Todo;

class TodoController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $todos = Todo::all($userId);
        include __DIR__ . '/../Views/index.php';
    }

    public function store($title) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        Todo::create(['title' => $title, 'user_id' => $userId]);
        header('Location: index.php?action=todos');
        exit;
    }

    public function toggle($id) {
        $todo = Todo::getById($id);
        $isCompleted = $todo['is_completed'] ? 0 : 1;
        Todo::update($id, ['is_completed' => $isCompleted]);
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['status' => 'success']);
            exit;
        }
        header('Location: index.php?action=todos');
        exit;
    }

    public function update($id, $title) {
        if (!empty($title)) {
            Todo::update($id, ['title' => $title]);
        }
        header('Location: index.php?action=todos');
        exit;
    }

    public function destroy($id) {
        Todo::delete($id);
        header('Location: index.php?action=todos');
        exit;
    }
}
