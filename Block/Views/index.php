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
        <!-- Если пользователь авторизован -->
        <a href="index.php?action=logout">Logout</a>
        <a href="index.php?action=todos">My Tasks</a>
    <?php else: ?>
        <!-- Если пользователь не авторизован -->
        <a href="index.php?action=login">Login</a>
        <a href="index.php?action=register">Register</a>
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
                <span class="task-title"><?= htmlspecialchars($todo['title']) ?></span> <!-- Задача, переданная из массива -->

                <?php if ($todo['is_completed']): ?>
                    <span>(Completed)</span>
                <?php else: ?>
                    <form action="index.php?action=toggle&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                        <button class="update_btn" type="submit">Complete</button>
                    </form>
                <?php endif; ?>

                <form action="index.php?action=delete&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                    <button class="cancel-button" type="submit">Delete</button>
                </form>

                <button class="edit-button">Update</button>

                <!-- Форма редактирования задачи (скрыта по умолчанию) -->
                <form class="edit-form" action="index.php?action=update&id=<?= $todo['id'] ?>" method="POST" style="display:none;">
                    <input type="text" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>
                    <button type="submit">Save</button>
                    <button type="button" class="cancel-button">Cancel</button>
                </form>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tasks available. Add a new task above.</p>
    <?php endif; ?>
</ul>


<script src='public/assets/app.js'></script>
</body>
</html>
