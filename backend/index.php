<?php
include "db.php";
global $db;
header('Content-Type: application/json');
// Получение задач
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $stmt = $db->prepare("SELECT * FROM tasks");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tasks);
    exit();
}
// Добавление задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['title'])) {
        $title = $input['title'];
        $stmt = $db->prepare("INSERT INTO tasks (title) VALUES (:title)");
        $stmt->execute(['title' => $title]);
        echo json_encode(['success' => true, 'message' => 'Task added']);
        exit();
        exit();
    } else {
        echo json_encode(['error' => 'Title not provided']);
        exit();
    }
}
// Удаление задачи
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $id = $input['id'];
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Task deleted']);
        exit();
    } else {
        echo json_encode(['error' => 'ID not provided for deletion']);
        exit();
    }
}


// Обновление задачи
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $id = $input['id'];
        $completed = isset($input['completed']) ? ($input['completed'] ? 1 : 0) : null;
        $title = isset($input['title']) ? $input['title'] : null;
        // Обновление только тех полей, которые переданы
        if ($title !== null) {
            $stmt = $db->prepare("UPDATE tasks SET title = :title WHERE id = :id");
            $stmt->execute(['title' => $title, 'id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Task title updated']);
        } else if ($completed !== null) {
            $stmt = $db->prepare("UPDATE tasks SET completed = :completed WHERE id = :id");
            $stmt->execute(['completed' => $completed, 'id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Task status updated']);
        } else {
            echo json_encode(['error' => 'No valid data provided']);
        }
        exit();
    } else {
        echo json_encode(['error' => 'ID not provided']);
        exit();
    }
}

?>
