document.addEventListener('DOMContentLoaded', () => {
    const taskList = document.getElementById('task-list');

    // Обработчик кнопки "Update"
    taskList.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', event => {
            const listItem = event.target.closest('li');
            const editForm = listItem.querySelector('.edit-form');
            const taskTitle = listItem.querySelector('.task-title');

            taskTitle.style.display = 'none';
            event.target.style.display = 'none';
            editForm.style.display = 'inline';
        });
    });

    // Обработчик кнопки "Cancel"
    taskList.querySelectorAll('.cancel-button').forEach(button => {
        button.addEventListener('click', event => {
            const listItem = event.target.closest('li');
            const editForm = listItem.querySelector('.edit-form');
            const taskTitle = listItem.querySelector('.task-title');
            const editButton = listItem.querySelector('.edit-button');

            taskTitle.style.display = 'inline';
            editForm.style.display = 'none';
            editButton.style.display = 'inline';
        });
    });

    // Обработчик клика по "Completed"
    taskList.querySelectorAll('.task-completed').forEach(element => {
        element.addEventListener('click', event => {
            const taskId = event.target.dataset.id;

            fetch(`index.php?action=toggle&id=${taskId}`, {
                method: 'POST',
            })
                .then(response => response.text())
                .then(() => {
                    location.reload();
                })
                .catch(error => console.error('Ошибка:', error));
        });
    });
});
