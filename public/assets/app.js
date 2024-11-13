document.addEventListener('DOMContentLoaded', function () {
        const taskInput = document.getElementById('taskInput');
        const addTaskButton = document.getElementById('addTaskButton');
        const taskList = document.getElementById('taskList');
        const logoutButton = document.getElementById('logoutButton')
        const profileButton = document.getElementById('profileButton')

// Функция для получения задач
        const getTasks = async () => {
            const response = await fetch('http://localhost/sites/Block/backend/index.php');
            const tasks = await response.json();
            console.log(tasks)
            renderTasks(tasks);
        };
// Функция для рендеринга задач
        const renderTasks = (tasks) => {
            taskList.innerHTML = ''; // Очищаем текущий список задач

            tasks.forEach(task => {
                const li = document.createElement('li');
                const taskText = document.createElement('p'); // Добавляем элемент для текста задачи
                taskText.textContent = task.title;
                taskText.classList.add('task_text'); // Добавляем класс для текста задачи

                // Добавляем кнопки
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Удалить';
                deleteButton.classList.add('delete_btn');
                deleteButton.onclick = () => deleteTask(task.id);

                const updateButton = document.createElement('button');
                updateButton.textContent = 'Редактировать';
                updateButton.classList.add('update_btn');
                updateButton.onclick = () => editTask(li, task.id, task.title);

                // Добавляем завершение задачи
                li.className = task.completed ? 'completed' : '';
                taskText.onclick = () => updateTask(task.id, !task.completed);

                // Добавляем элементы в список
                li.appendChild(taskText);
                li.appendChild(updateButton);
                li.appendChild(deleteButton);

                taskList.appendChild(li);
            });
        };
// Функция для добавления задачи
        const addTask = async () => {
            const title = taskInput.value;
            if (!title) return;
            await fetch('http://localhost/sites/Block/backend/index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({title})
            });
            taskInput.value = '';
            getTasks();
        };
// Функция для удаления задачи
        const deleteTask = async (id) => {
            await fetch('http://localhost/sites/Block/backend/index.php', {
                method: 'DELETE',
                headers: {
                    'Content-type': 'application/json'
                },
                body: JSON.stringify({id})
            });
            getTasks();
        };
// Функция для обновления статуса задачи
        const updateTask = async (id, completed) => {
            await fetch('http://localhost/sites/Block/backend/index.php', {
                method: 'PUT',
                headers: {
                    'Content-type': 'application/json'
                },
                body: JSON.stringify({id, completed})
            });
            getTasks();
        };
// Функция для редактирования задачи
        const editTask = (li, id, currentTitle) => {
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentTitle;
            input.classList.add('edit_input');

            // Создаем кнопку для сохранения изменений
            const saveButton = document.createElement('button');
            saveButton.textContent = 'Сохранить';
            saveButton.classList.add('save_btn');
            saveButton.onclick = async () => {
                const newTitle = input.value;
                if (newTitle && newTitle !== currentTitle) { // Проверяем, что заголовок был изменен
                    await fetch('http://localhost/sites/Block/backend/index.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({id, title: newTitle}) // Передаем только ID и новый заголовок
                    });
                    getTasks(); // Перезагружаем список задач
                }
            };

            // Заменяем текст задачи на поле ввода и кнопку "Сохранить"
            li.innerHTML = '';
            li.appendChild(input);
            li.appendChild(saveButton);
            input.focus(); // Фокус на поле ввода
        };
// Подключение обработчика события
        addTaskButton.onclick = addTask;
// Инициализация списка задач при загрузке страницы
        getTasks();
//проверка есть ли кнопка или она найдена или нет
        if (logoutButton) {
            logoutButton.addEventListener('click', function () {
                localStorage.removeItem('isLoggedIn')
                window.location.href = 'login.html'
            })
        }
        profileButton.addEventListener('click', function () {
            window.location.href = 'profile.html'
        })
//
        if (window.location.pathname === '/index.html' && localStorage.getItem('isLoggedIn')) {
            window.location.href = 'login.html'
        }
    })
