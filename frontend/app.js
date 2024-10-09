const taskInput = document.getElementById('taskInput');
const addTaskButton = document.getElementById('addTaskButton');
const taskList = document.getElementById('taskList');

// Функция для получения задач
const getTasks = async () => {
    const response = await fetch('http://localhost/sites/Block/backend/index.php');
    const tasks = await response.json();
    renderTasks(tasks);
};
// Функция для рендеринга задач
const renderTasks = (tasks) => {
    taskList.innerHTML = ''; // Очищаем текущий список задач
    tasks.forEach(task => {
        const li = document.createElement('li');
        li.textContent = task.title;
        li.classList.add(".li_flex")
        // Добавление кнопки для удаления задачи
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Удалить';
        deleteButton.classList.add('delete_btn')
        deleteButton.onclick = () => deleteTask(task.id);
        li.appendChild(deleteButton);

        // Добавление кнопки для удаления задачи
        const updateButton = document.createElement('button');
        updateButton.textContent = 'Редактировать';
        updateButton.classList.add('update_btn')
        li.appendChild(updateButton);
        // Отмечаем задачу как завершенную
        li.className = task.completed ? 'completed' : '';
        li.onclick = () => updateTask(task.id, !task.completed); // Меняем статус завершенности
        taskList.appendChild(li);
    });
};

// Функция для добавления задачи
const addTask = async () => {
    const title = taskInput.value;
    if (!title) return; // Проверяем, не пустое ли поле ввода
    await fetch('http://localhost/sites/Block/backend/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ title }) // Кодируем параметры
    });
    taskInput.value = ''; // Очищаем поле ввода
    getTasks(); // Обновляем список задач
};

// Функция для удаления задачи
const deleteTask = async (id) => {
    await fetch('http://localhost/sites/Block/backend/index.php', {

        method: 'DELETE',
        body: new URLSearchParams({ id }) // Кодируем параметры
    });
    getTasks(); // Обновляем список задач
};

// Функция для обновления статуса задачи
const updateTask = async (id, completed) => {
    await fetch('http://localhost/sites/Block/backend/index.php', {
        method: 'PUT',
        body: new URLSearchParams({ id, completed }) // Кодируем параметры
    });
    getTasks(); // Обновляем список задач
};

// Подключение обработчика события
addTaskButton.onclick = addTask;

// Инициализация списка задач при загрузке страницы
getTasks();
