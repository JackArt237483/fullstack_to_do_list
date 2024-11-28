<?php

namespace User\Block\Controllers;

use TodoRepositoryInterface;
use AuthMiddleware;

class TodoController {
    private TodoRepositoryInterface $todoRepository;
    public function __construct(TodoRepositoryInterface $todoRepository){
        $this->todoRepository = $todoRepository;
    }
    public function index():void {
        AuthMiddleware::check();

        $userId = $_SESSION['user_id']; // Получаем ID текущего пользователя из сессии
        $todos = $this->todoRepository->getAllBuUserId($userId);

        include __DIR__ . '/../Views/index.php';
    }
    public function store($title) {
        AuthMiddleware::check();
        $userId = $_SESSION['user_id'];
        $this->todoRepository->create(['title' => $title, 'user_id' => $userId]);
        header('Location: index.php?action=todos');
        exit;
    }
    public function update(int $id, string $title = null, ?bool $isCompleted = null):void {
        AuthMiddleware::check();

        $data = [];
        if (!is_null($title)) {
            $data['title'] = $title;
        }
        //проверка на наличие заголовки
        if(!is_null($isCompleted)) {
            $data['is_completed'] = $isCompleted;
        }
        //проверка на наличие завершения
        if(!empty($data)){
            $this->todoRepository->update($id,$data);
        }
        // проверка то что все в порядке
        header('Location: index.php?action=todos');
        exit;
    }
    public function toggle($id) {

        AuthMiddleware::check();

        $todo = $this->todoRepository->getById($id);
        if($todo){

        }
        $isCompleted = $todo['is_completed'] ? 0 : 1;
        $this->todoRepository->update($id, ['is_completed' => $isCompleted]);
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
