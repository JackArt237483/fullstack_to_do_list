<?php
    // Инициализация подключения к базе данных
    include 'db.php';  // подключение базы данных

    // Подключаем API контроллер
    include 'APIController.php';

    global $db;

    // Инициализация API контроллера
    $api = new APIController($db);

    // Получение данных из запроса
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;

    // Обработка запроса
    $response = $api->handleRequest($action, $data);

    // Ответ в формате JSON
    echo json_encode($response);

?>
