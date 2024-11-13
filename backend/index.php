<?php
include "db.php";
session_start();
global $db;

class Task{
    private $db;
    private $user_id;

    public function __construct($db){
        $this->db = $db;
        $this->user_id = $_SESSION['user_id'] ?? null;

        if(!$this->user_id){
            // Печатаем JSON с ошибкой, если пользователь не авторизован
            echo json_encode(['error' => 'Пользователь не авторизован']);
            exit();
        }
    }

    // Получить задачи
    public function getTasks(){
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $this->user_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);  // Отправляем список задач в JSON
    }

    // Добавить задачу
    public function postTasks(){
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'] ?? null;

        if ($title) {
            $stmt = $this->db->prepare('INSERT INTO tasks (user_id, title) VALUES (:user_id, :title)');
            $stmt->execute([
                'user_id' => $this->user_id,
                'title' => $title
            ]);
            echo json_encode(['success' => true]);  // Успешный ответ
        } else {
            echo json_encode(['error' => 'Заполните поле задачи']);  // Ошибка, если название пустое
        }
    }

    // Обновить задачу
    public function putTasks(){
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        $title = $data['title'] ?? null;
        $completed = $data['completed'] ?? null;

        if ($id && isset($completed)) {
            $stmt = $this->db->prepare('UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id');
            $stmt->execute([
                'completed' => $completed,
                'id' => $id,
                'user_id' => $this->user_id
            ]);
            echo json_encode(['success' => true]);  // Успешно обновили задачу
        } elseif ($id && $title) {
            $stmt = $this->db->prepare('UPDATE tasks SET title = :title WHERE id = :id AND user_id = :user_id');
            $stmt->execute([
                'title' => $title,
                'id' => $id,
                'user_id' => $this->user_id
            ]);
            echo json_encode(['success' => true]);  // Успешно обновили задачу
        } else {
            echo json_encode(['error' => 'Недостаточно данных для обновления']);  // Ошибка при обновлении
        }
    }

    // Удалить задачу
    public function deleteTasks(){
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if ($id) {
            $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
            $stmt->execute(['id' => $id, 'user_id' => $this->user_id]);
            echo json_encode(['success' => true]);  // Успешно удалили задачу
        } else {
            echo json_encode(['error' => 'Не указана задача для удаления']);  // Ошибка при удалении
        }
    }
}

// Создаем объект задачи
$task = new Task($db);
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $task->getTasks();
        break;
    case 'POST':
        $task->postTasks();
        break;
    case 'PUT':
        $task->putTasks();
        break;
    case 'DELETE':
        $task->deleteTasks();
        break;
    default:
        echo json_encode(['error' => 'Запрос не поддерживается']);
}
?>
