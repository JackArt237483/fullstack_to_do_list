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