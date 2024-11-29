<?php

namespace User\Block\Repositories;
use DataBaseinterface;
use TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface{
    private DataBaseinterface $db;
    public function __construct(DataBaseinterface $db)
    {
        $this->db = $db;
    }
    public function getAllByUserId(int $userId): array
    {
        return $this->db->query(
            'SELECT * FROM todos WHERE user_id = :user_id ORDER BY created_at DESC',
            ['user_id' => $userId]
        );
    }
    public function create(array $data): void
    {
        $this->db->execute(
            'INSERT INTO todos (title, user_id) VALUES (:title, :user_id)',
            $data
        );
    }
    public function update(int $id, array $data): void
    {
        foreach ($data as $key => $value) {
            $this->db->execute(
                'UPDATE todos SET $key = :value WHERE id = :id',
                ['value' => $value, 'id' => $id]
            );
    }
    }
    public function delete(int $id): void{
        $this->db->execute('DELETE FROM todos WHERE id = :id', ['id' => $id]);
    }
    public function getById(int $id): ?array
    {
        $result = $this->db->query('SELECT * FROM todos WHERE id = :id', ['id' => $id]);
        return $result[0] ?? null;
    }
}

?>