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
        <a href="index.php?action=logout">Logout</a>
        <a href="index.php?action=todos">My Tasks</a>
    <?php endif; ?>
</nav>

<h1>ToDo List</h1>
<!-- Форма добавления новой задачи -->
<form action="index.php?action=create" method="POST">
    <input type="text" name="title" placeholder="New task" required>
    <button type="submit">Add</button>
</form>

<!-- Список задач -->
<ul id="task-list">
    <?php if (!empty($todos) && is_array($todos)): ?>
        <?php foreach ($todos as $todo): ?>
            <li data-id="<?= $todo['id'] ?>">
                <!-- Текст задачи -->
                <span class="task-title"><?= htmlspecialchars($todo['title']) ?></span>

                <?php if ($todo['is_completed']): ?>
                    <!-- Отображение Completed -->
                    <span class="task-completed" data-id="<?= $todo['id'] ?>">Сделанно мен</span>
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
        <p>No tasks available. Add a new task above.</p>
    <?php endif; ?>
</ul>

<script src="public/assets/app.js"></script>
</body>
</html>
