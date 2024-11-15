<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../../public/assets/need.css">
</head>
<body>
<div class="container">
    <h1>Регистрация</h1>
    <form id="registerForm" action="../../backend/data.php" method="POST">
        <input type="text" name="username" id="username" class="form-input" placeholder="Имя пользователя" required>
        <input type="email" name="email" id="email" class="form-input" placeholder="Электронная почта" required>
        <input type="tel" name="phone" id="phone" class="form-input" placeholder="Номер телефона" required>
        <input type="password" name="password" id="password" class="form-input" placeholder="Пароль" required>
        <button type="submit" class="form-button">Зарегистрироваться</button>
    </form>

    <p>Уже есть аккаунт? <a href="login.php" class="form-link">Войти</a></p>

    <div id="registerMessage"></div>
</div>
</body>
<script src="../../public/assets/data.js"></script>
</html>
