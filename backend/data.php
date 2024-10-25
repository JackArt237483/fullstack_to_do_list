<?php
    session_start();
    include "db.php";
    global $db;
    header('Content-type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'),true);
        $action = $data['action'] ?? null;
        if ($action === 'register') {
            registerUser($db,$data);
        } elseif ($action === 'login') {
            loginUser($db,$data);
        } else {
            echo json_encode(['error' => 'Неправильное действие. Пожалуйста, проверьте отправляемые данные.']);
        }
        exit();
    }
    // Функция для регистрации пользователя
    function registerUser($db, $data): void{
        // Получаем данные из POST-запроса
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $phone = $data['phone'] ?? null;
        $email = $data['email'] ?? null;
        // Проверяем, что все данные заполнены
        if ($username && $password && $phone && $email) {
            // Проверяем, существует ли уже пользователь с таким email
            $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);  // Исправлено: используем правильный метод execute
            // Если пользователь с таким email уже существует
            if ($stmt->rowCount() > 0) {
                echo json_encode(['error' => 'Пользователь с таким email уже существует']);
                return;
            }
            // Хэшируем пароль
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Вставляем нового пользователя в базу данных
            $stmt = $db->prepare('INSERT INTO users (username, password, phone, email) VALUES (:username, :password, :phone, :email)');
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'phone' => $phone,
                'email' => $email
            ]);
            // Возвращаем успешный ответ
            echo json_encode(['success' => true, 'message' => 'Регистрация успешна']);
        } else {
            // Если не все поля заполнены
            echo json_encode(['error' => 'Заполните все поля']);
        }
    }
    // Функция для входа в систему
    function loginUser($db,$data): void{
        // Получаем данные из POST-запроса
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        // Проверяем, что все поля заполнены
        if ($username && $password) {
            // Проверяем, существует ли пользователь с таким именем
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Проверяем, совпадает ли пароль
                if (password_verify($password, $user['password'])) {
                    // Сохраняем данные о пользователе в сессию
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // Возвращаем успешный ответ
                    echo json_encode(['success' => true, 'message' => 'Вход успешен', 'user' => $user['username']]);
                } else {
                    // Неверный пароль
                    echo json_encode(['error' => 'Неверный пароль']);
                }
            } else {
                // Пользователь с таким именем не найден
                echo json_encode(['error' => 'Пользователь не найден']);
            }
        } else {
            // Если не все поля заполнены
            echo json_encode(['error' => 'Заполните все поля']);
        }
    }
?>