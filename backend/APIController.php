<?php
include "db.php";
include 'index.php';
include 'data.php';


global $db;

class APIController
{
    private $user;
    private $task;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->task = new Task($db);
    }

    public function handleRequest($action, $data)
    {
        switch ($action) {
            case 'register':
                return $this->user->register($data);
            case 'login':
                return $this->user->login($data);
            case 'update':
                return $this->user->update($data);
            case 'getTask':
                return $this->task->getTask();
            case 'createTask':
                return $this->task->createTask($data);
            case 'updateTask':
                return $this->task->updateTask($data);
            case 'deleteTask':
                return $this->task->deleteTask($data);
            default:
                return ['error' => 'Неправильное действие. Пожалуйста, проверьте отправляемые данные.'];
        }
    }
}
?>