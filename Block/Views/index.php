<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="public/assets/style.css">
</head>
<body>
<!-- Верхняя панель с навигацией -->
<nav>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a class="link_logout" href="index.php?action=logout">Logout</a>
    <?php endif; ?>
</nav>

<h1>ToDo List</h1>

<div class="flex_inline">
    <!-- Форма добавления новой задачи -->
    <form class="select_form" action="index.php?action=create" method="POST">
        <input type="text" name="title" placeholder="New task" required>

        <label for="category">Категория:</label>
        <select name="category" id="category">
            <option value="Работа">Работа</option>
            <option value="Личное">Личное</option>
            <option value="Учеба">Учеба</option>
        </select>

        <label for="priority">Приоритет:</label>
        <select name="priority" id="priority">
            <option value="1">Высокий</option>
            <option value="2">Средний</option>
            <option value="3">Низкий</option>
        </select>

        <button type="submit">Add</button>
    </form>
    <!-- Список задач -->
    <ul id="task-list">
        <?php if (!empty($todos) && is_array($todos)): ?>
            <?php foreach ($todos as $todo): ?>
                <li data-id="<?= $todo['id'] ?>">
                    <!-- Текст задачи -->
                    <span class="task-title"><?= htmlspecialchars($todo['title']) ?></span>
                    <?= htmlspecialchars($todo['category'])?>
                    <?= $todo['priority'] === 1 ? 'Высокий' : ($todo['priority'] === 2 ? 'Средний' : 'Низкий') ?>

                    <?php if ($todo['is_completed']): ?>
                        <!-- Отображение Completed -->
                        <span class="task-completed" data-id="<?= $todo['id'] ?>">Сделанно меня</span>
                    <?php else: ?>
                        <!-- Кнопка Complete -->
                        <form action="index.php?action=toggle&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                            <button class="update_btn" type="submit">Сделанно</button>
                        </form>
                    <?php endif; ?>

                    <!-- Кнопка для перехода в режим редактирования -->
                    <button class="edit-button">Update</button>

                    <!-- Форма редактирования задачи (скрыта по умолчанию) -->
                    <form class="edit-form" action="index.php?action=update&id=<?= $todo['id'] ?>" method="POST" style="display:none;">
                        <input type="text" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>

                        <select name="category">
                            <option value="Работа" <?= $todo['category'] === 'Работа' ? 'selected' : '' ?>>Работа</option>
                            <option value="Личное" <?= $todo['category'] === 'Личное' ? 'selected' : '' ?>>Личное</option>
                            <option value="Учеба" <?= $todo['category'] === 'Учеба' ? 'selected' : '' ?>>Учеба</option>
                        </select>

                        <select name="priority">
                            <option value="1" <?= $todo['priority'] === 'Низкий' ? 'selected' : '' ?>>Низкий</option>
                            <option value="2" <?= $todo['priority'] === 'Средний' ? 'selected' : '' ?>>Средний</option>
                            <option value="3" <?= $todo['priority'] === 'Высокий' ? 'selected' : '' ?>>Высокий</option>
                        </select>

                        <button type="submit">Save</button>
                        <button type="button" class="cancel-button">Cancel</button>
                    </form>

                    <!-- Кнопка удаления задачи -->
                    <form action="index.php?action=delete&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                        <button class="cancel-button" type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Задач мужик нету нужно из добавить</p>
        <?php endif; ?>
    </ul>
</div>

<script src="public/assets/app.js"></script>
</body>
</html>
