<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="public/assets/account.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1>Личный кабинет</h1>
        <nav class="nav">
            <ul>
                <li><a href="#profile">Профиль</a></li>
                <li><a href="index.php?action=todos">Задачи</a></li>
                <li><a href="index.php?action=logout">Выйти</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="main">
    <!-- Секция профиля -->
    <section id="profile" class="section">
        <h2>Профиль</h2>
        <div class="profile-info">
            <form action="index.php?action=update_profile" method="POST">

                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label for="phone">Телефон:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

                <label for="phone">Пароль:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['password']) ?>" required>

                <button type="submit">Сохранить изменения</button>
            </form>
        </div>
    </section>

    <!-- Секция задач -->
    <section id="tasks" class="section">
        <h2>Мои задачи</h2>
        <ul class="tasks">
            <?php if (!empty($todos) && is_array($todos)): ?>
                <?php foreach ($todos as $todo): ?>
                    <li>
                        <strong>Задача:</strong> <?= htmlspecialchars($todo['title']) ?><br>
                        <strong>Категория:</strong> <?= htmlspecialchars($todo['category']) ?><br>
                        <strong>Приоритет:</strong> <?= $todo['priority'] === 1 ? 'Высокий' : ($todo['priority'] === 2 ? 'Средний' : 'Низкий') ?><br>
                        <strong>Статус:</strong> <?= $todo['is_completed'] ? 'Выполнено' : 'В процессе' ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>У вас нет задач</li>
            <?php endif; ?>
        </ul>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <p>© <?= date('Y') ?> Личный кабинет. Все права защищены.</p>
    </div>
</footer>
</body>
</html>
