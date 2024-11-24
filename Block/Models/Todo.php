<?php

namespace User\Block\Models;

use PDO;

class Todo
{
    private static function connect()
    {
        $dbPath = __DIR__ . '/../../config/identifier.sqlite';
        return new PDO('sqlite:' . $dbPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function all()
    {
        $db = self::connect();
        $stmt = $db->query('SELECT * FROM todos ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = self::connect();
        $stmt = $db->prepare('INSERT INTO todos (title) VALUES (:title)');
        $stmt->execute(['title' => $data['title']]);
    }

    public static function update($id, $data)
    {
        $db = self::connect();

        if (isset($data['is_completed'])) {
            // Обновление статуса
            $stmt = $db->prepare('UPDATE todos SET is_completed = :is_completed WHERE id = :id');
            $stmt->execute(['is_completed' => $data['is_completed'], 'id' => $id]);
        }

        if (isset($data['title'])) {
            // Обновление заголовка
            $stmt = $db->prepare('UPDATE todos SET title = :title WHERE id = :id');
            $stmt->execute(['title' => $data['title'], 'id' => $id]);
        }
    }

    public static function delete($id)
    {
        $db = self::connect();
        $stmt = $db->prepare('DELETE FROM todos WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
