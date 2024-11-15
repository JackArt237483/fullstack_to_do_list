<?php
    namespace App\Controllers;

    use App\Models\TaskModel;
    use App\Core\Controller;

    class TaskController extends Controller {
        private $taskModel;
        private $user_id;
        public function __construct($db)
        {
            $this->taskModel = new TaskModel($db);
            $this->user_id = $_SESSION['action'] ?? null;

            if(!$this->user_id){
                // Печатаем JSON с ошибкой, если пользователь не авторизован
                echo json_encode(['error' => 'Пользователь не авторизован']);
                exit();
            }
        }
        public function getTasks()
        {
            $tasks = $this->taskModel->getTasks($this->user_id);
            echo json_encode($tasks);
        }
        public function postTasks(){
            $data = json_decode(file_get_contents('php://input'), true);
            $title = $data['title'] ?? null;

            if($title) {
                $this->taskModel->addTask($this->user_id, $title);
                echo json_encode(['success' => true]);  // Успешный ответ
            } else {
                echo json_encode(['error' => 'Заполните поле задачи']);  // Ошибка, если название пустое
            }
        }
        public function putTask()
        {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
            $title = $data['title'] ?? null;
            $completed = $data['completed'] ?? null;

            if($id && ($title || isset($completed))) {
                $this->taskModel->updateTask($this->user_id,$id,$title,$completed);
                echo json_encode(['success' => true]);  // Успешно обновили задачу
            } else {
                echo json_encode(['error' => 'Недостаточно данных для обновления']);  // Ошибка при обновлении
            }
        }
        public function deleteTask()
        {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;

            if ($id) {
                $this->taskModel->deleteTask($this->user_id,$id);
                echo json_encode(['success' => true]);  // Успешно удалили задачу
            } else {
                echo json_encode(['error' => 'Не указана задача для удаления']);  // Ошибка при удалении
            }
        }
    }
?>