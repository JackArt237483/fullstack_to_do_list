<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список дел</title>
    <link rel="stylesheet" href="/public/assets/style.css"> <!-- Подключение CSS -->
</head>
<body>
<div class="container">
    <h1>Список дел</h1>

        <input type="text" id="taskInput" placeholder="Введите задачу..." />
        <button id="addTaskButton">Добавить задачу</button>



        <ul id="taskList"></ul>

        <div class="two_button">
            <button id="logoutButton">Выйти</button>
            <button id="profileButton">Личный кабинет</button>
        </div>

</div>
<script src="/public/assets/app.js"></script>
</body>
</html>
