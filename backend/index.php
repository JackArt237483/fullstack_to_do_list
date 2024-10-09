<?php
include "db.php";
global $db;
header('Content-Type: application/json');
// Получение задач с таблицы
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    // выбираем все задачи
    $stmt = $db->prepare("SELECT * FROM tasks");
    $stmt->execute(); // Не забывайте выполнять запрос
    // извлекаем все задачи в виде массива PHP
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // преобразование в JSON формате
    echo json_encode($tasks);
    // завершение скрипта
    exit();
}

// ОТПРАВКА НА СЕРВЕР ДЛЯ ЭТОГО ЗАПРОС
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        // подготовка SQL запрос для вставки в таблицу
        $stmt = $db->prepare("INSERT INTO tasks (title) VALUES (:title)");
        // выполняем запрос подготовленный
        $stmt->execute(['title' => $title]);
        // завершение скрипта
        exit();
    } else {
        echo json_encode(['error' => 'Title not provided']); // Вернуть ошибку, если title не установлен
        exit();
    }
}

// Удаление конкретного элемента
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['id'])) {
        // ПОЛУЧЕНИЕ ЭЛЕМЕНТА ;
        $id = $_DELETE['id'];
        // ПОДГОТОВКА SQL ЗАПРОСА ДЛЯ УДАЛЕНИЯ ЭЛЕМЕНТА
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        // Выполнение запроса
        $stmt->execute(["id" => $id]);
        exit();
    } else {
        echo json_encode(['error' => 'ID not provided for deletion']); // Вернуть ошибку, если id не установлен
        exit();
    }
}

// ОБНОВЛЕНИЕ ЗАДАЧИ ПО ID
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    if (isset($_PUT['id']) && isset($_PUT['completed'])) {
        $id = $_PUT['id'];
        $completed = $_PUT['completed'] ? 1 : 0;

        $stmt = $db->prepare('UPDATE tasks SET completed = :completed WHERE id = :id');
        $stmt->execute(['completed' => $completed, 'id' => $id]);
        exit();
    } else {
        echo json_encode(['error' => 'ID or completed status not provided']); // Вернуть ошибку, если id или completed не установлены
        exit();
    }
}
?>
