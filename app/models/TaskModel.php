<?php
    namespace App\Models;
    global $db;
    use PDO;
    class TaskModel {
        private $db;
        public function __construct($db)
        {
            $this->db = $db;
        }
        public function getTasks ($user_id){
            $stmt = $this->db->prepare('SELECT * FROM tasks WHERE user_id = :user_id');
            $stmt->execute(['user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function addTask ($user_id,$title)
        {
            $stmt = $this->db->excute('INSERT INTO tasks (user_id,title) values (:user_id, :title)');
            $stmt->excute([
                '$user_id' => $user_id,
                '$title' => $title
            ]);
            return $this->db->LastInsetId();
        }
        public function updateTask($user_id,$id,$title,$completed ): true
        {
            if ($title !== null) {
                $stmt = $this->db->prepare('UPDATE tasks SET title = :title WHERE id = :id AND user_id = :user_id');
                $stmt->execute([
                    'title' => $title,
                    'id' => $id,
                    'user_id' => $user_id,
                ]);
                return true;
            }

            if ($completed !== null) {
                $stmt = $this->db->prepare('UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id');
                $stmt->execute([
                    'completed' => $completed,
                    'id' => $id,
                    'user_id' => $user_id,
                ]);
                return true;
            }

            return true;
        }
        public function deleteTask($user_id,$id)
        {
            $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
            $stmt->execute(['id' => $id, 'user_id' => $user_id]);
            return $stmt->rowCount() > 0;
        }
    }
?>