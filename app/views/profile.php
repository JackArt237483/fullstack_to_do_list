<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../../public/assets/need.css">
</head>
<body>
<div class="dashboard-container">
    <h1 class="dashboard-title">Личный кабинет</h1>

    <div class="user-info-section">
        <h2 class="section-title">Персональная информация</h2>
        <form id="user-form" class="user-form">
            <label class="form-label" for="username">Имя пользователя:</label>
            <input type="text" id="username" class="profile-input user-input" placeholder="Имя">

            <label class="form-label" for="email">Email:</label>
            <input type="email" id="email" class="profile-input user-input" placeholder="Почта">

            <label class="form-label" for="phone">Телефон:</label>
            <input type="tel" id="phone" class="profile-input user-input" placeholder="Номер телефона">

            <label class="form-label" for="password">Пароль:</label>
            <input type="password" id="password" class="profile-input user-input" placeholder="Измените пароль">

            <button type="submit" class="save-button">Сохранить изменения</button>
        </form>
    </div>

    <div class="activity-section">
<!--        <h2 class="section-title">Последние действия</h2>-->
<!--        <ul class="activity-list">-->
<!--            <li class="activity-item">Вход в систему: 2024-10-25 10:00</li>-->
<!--            <li class="activity-item">Изменение email: 2024-10-24 14:32</li>-->
<!--            &lt;!&ndash; Пример событий &ndash;&gt;-->
<!--        </ul>-->
    </div>

     <button id="taskButton">Задачи</button>
</div>

<script src="../../public/assets/data.js"></script>
</body>
</html>