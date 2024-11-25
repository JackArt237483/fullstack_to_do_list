<?php

namespace User\Block\Controllers;

use User\Block\Models\Todo;

class TodoController {
    public function index() {
        // Проверяем и запускаем сессию, если она ещё не активна
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Проверяем, авторизован ли пользователь
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id']; // Получаем ID текущего пользователя

        // Получаем задачи текущего пользователя
        $todos = Todo::all($userId); // Возвращает массив задач для этого пользователя

        // Подключаем шаблон для отображения задач
        include __DIR__ . '../../Views/index.php';
    }


    public function store($title) {
        // Проверяем и запускаем сессию, если она ещё не активна
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Проверяем, авторизован ли пользователь
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id']; // Получаем ID текущего пользователя

        // Создаем задачу для текущего пользователя
        Todo::create(['title' => $title, 'user_id' => $userId]);

        // Перенаправляем обратно на страницу задач
        header('Location: index.php?action=todos');
        exit;
    }

    public function update($id, $data) {
        // Обновляем задачу
        Todo::update($id, $data);

        // Перенаправляем обратно на страницу задач
        header('Location: index.php?action=todos');
        exit;
    }

    public function destroy($id) {
        // Удаляем задачу
        Todo::delete($id);

        // Перенаправляем обратно на страницу задач
        header('Location: index.php?action=todos');
        exit;
    }
}
