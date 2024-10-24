<?php
session_start();
include "db.php";
global $db;
header('Content-type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получаем задачи для текущего пользователя
    $stmt = $db->prepare('SELECT * FROM tasks WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tasks);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'] ?? null;

    if ($title) {
        $stmt = $db->prepare('INSERT INTO tasks (user_id, title) VALUES (:user_id, :title)');
        $stmt->execute([
            'user_id' => $user_id,
            'title' => $title
        ]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Заполните поле задачи']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    $title = $data['title'] ?? null;
    $completed = $data['completed'] ?? null;

    if ($id && isset($completed)) {
        $stmt = $db->prepare('UPDATE tasks SET completed = :completed WHERE id = :id AND user_id = :user_id');
        $stmt->execute([
            'completed' => $completed,
            'id' => $id,
            'user_id' => $user_id
        ]);
        echo json_encode(['success' => true]);
    } elseif ($id && $title) {
        $stmt = $db->prepare('UPDATE tasks SET title = :title WHERE id = :id AND user_id = :user_id');
        $stmt->execute([
            'title' => $title,
            'id' => $id,
            'user_id' => $user_id
        ]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Недостаточно данных']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if ($id) {
        $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $id, 'user_id' => $user_id]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Не указана задача для удаления']);
    }
}
?>
