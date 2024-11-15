<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../../public/assets/need.css"> <!-- Подключите ваш CSS файл -->
</head>
<body>

<div class="login-container">
    <h1 class="login-title">Вход</h1>
    <form id="loginForm" class="login-form" action="../../backend/data.php" method="POST">
        <input type="text" id="login_username" class="login-input" placeholder="Логин" required>
        <input type="password" id="login_password" class="login-input" placeholder="Пароль" required>
        <button type="submit" class="login-button">Войти</button>
    </form>
    <p class="login-text">Еще нет аккаунта? <a href="register.php" class="login-link">Зарегистрируйтесь</a></p>

    <div id="loginMessage"></div>
</div>

<script src="../../public/assets/data.js"></script>
</body>
</html>