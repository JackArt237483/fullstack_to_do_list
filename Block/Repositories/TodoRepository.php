<?php

namespace User\Block\Repositories;
use User\Block\Interfaces\DatabaseInterface;
use User\Block\Interfaces\TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface {
    private DatabaseInterface $db;
    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }
    public function getAllByUserId(int $userId): array
    {
        return $this->db->query(
            'SELECT * FROM todos WHERE user_id = :user_id ORDER BY priority ASC ,created_at DESC',
            ['user_id' => $userId]
        );
    }
    public function create(array $data): void
    {
        $this->db->execute(
            'INSERT INTO todos (title, user_id, category, priority) VALUES (:title, :user_id, :category, :priority)',
            $data
        );
    }
    public function update(int $id, array $data): void
    {
        $setPart = [];
        $params = ['id' => $id]; // обязательно добавляем id в параметры

        foreach ($data as $key => $value) {
            $setPart[] = "$key = :$key";
            $params[$key] = $value; // добавляем параметр для каждого поля
        }

        $setString = implode(', ', $setPart); // Собираем строку SET

        $sql = "UPDATE todos SET $setString WHERE id = :id"; // Собираем полный SQL запрос

        $this->db->execute($sql, $params); // Выполняем запрос с параметрами
    }
    public function delete(int $id): void
    {
        
        $this->db->execute('DELETE FROM todos WHERE id = :id', ['id' => $id]);
    }
    public function getById(int $id): ?array
    {
        $result = $this->db->query('SELECT * FROM todos WHERE id = :id', ['id' => $id]);
        return $result[0] ?? null;
    }

}
?>