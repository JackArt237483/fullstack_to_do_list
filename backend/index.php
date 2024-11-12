<?php
include "db.php";
global $db;

header('Content-type: application/json');
class Task
{
    private $db;
    private $user_id;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user_id = $_SESSION['user_id'] ?? null;
    }

    public function getTask()
    {
        if (!$this->user_id) {
            return ['error' => 'Пользователь не авторизован'];
        }

        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($data): array
    {
        $title = $data['title'] ?? null;

        if (!$this->user_id) {
            return ['error' => 'Пользователь не авторизован'];
        }

        if ($title) {
            $stmt = $this->db->prepare('INSERT INTO tasks (user_id, title) VALUES (:user_id, :title)');
            $stmt->execute(['user_id' => $this->user_id, 'title' => $title]);
            return ['success' => true];
        }

        return ['error' => 'Заполните поле задачи'];
    }

    public function updateTask($data): array
    {
        $id = $data['id'] ?? null;
        $title = $data['title'] ?? null;
        $completed = $data['completed'] ?? null;

        if (!$this->user_id) {
            return ['error' => 'Пользователь не авторизован'];
        }

        if ($id && isset($completed)) {
            $stmt = $this->db->prepare('UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id');
            $stmt->execute(['completed' => $completed, 'id' => $id, 'user_id' => $this->user_id]);
            return ['success' => true];
        } elseif ($id && $title) {
            $stmt = $this->db->prepare('UPDATE tasks SET title = :title WHERE id = :id AND user_id = :user_id');
            $stmt->execute(['title' => $title, 'id' => $id, 'user_id' => $this->user_id]);
            return ['success' => true];
        }

        return ['error' => 'Недостаточно данных'];
    }

    public function deleteTask($data): array
    {
        $id = $data['id'] ?? null;

        if (!$this->user_id) {
            return ['error' => 'Пользователь не авторизован'];
        }

        if ($id) {
            $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
            $stmt->execute(['id' => $id, 'user_id' => $this->user_id]);
            return ['success' => true];
        }

        return ['error' => 'Не указана задача для удаления'];
    }
}
