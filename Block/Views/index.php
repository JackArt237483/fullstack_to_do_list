<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="public/assets/style.css">
</head>

<body>

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
                    <span>(Completed)</span>
                <?php else: ?>
                    <form action="index.php?action=toggle&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                        <button type="submit">Complete</button>
                    </form>
                <?php endif; ?>

                <!-- Кнопка удаления задачи -->
                <form action="index.php?action=delete&id=<?= $todo['id'] ?>" method="POST" style="display:inline;">
                    <button type="submit">Delete</button>
                </form>

                <!-- Кнопка для перехода в режим редактирования -->
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const taskList = document.getElementById('task-list');

        // Добавляем обработчики на все кнопки "Update"
        taskList.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', event => {
                const listItem = event.target.closest('li');
                const editForm = listItem.querySelector('.edit-form');
                const taskTitle = listItem.querySelector('.task-title');

                // Скрываем текст задачи и показываем форму редактирования
                taskTitle.style.display = 'none';
                event.target.style.display = 'none'; // Скрываем кнопку "Update"
                editForm.style.display = 'inline';
            });
        });

        // Добавляем обработчики на все кнопки "Cancel"
        taskList.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', event => {
                const listItem = event.target.closest('li');
                const editForm = listItem.querySelector('.edit-form');
                const taskTitle = listItem.querySelector('.task-title');
                const editButton = listItem.querySelector('.edit-button');

                // Показываем текст задачи и кнопку "Update", скрываем форму редактирования
                taskTitle.style.display = 'inline';
                editForm.style.display = 'none';
                editButton.style.display = 'inline';
            });
        });
    });
</script>
</body>
</html>
