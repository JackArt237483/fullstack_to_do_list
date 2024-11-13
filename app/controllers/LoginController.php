<?php

    namespace App\Controller;
    use Controller;

    class LoginController extends Controller{
        public function showLoginForm(){
            $this->view('login');
        }
        // Функция для входа в систему
        public function login($data): void{
            // Получаем данные из POST-запроса
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;

            // Проверяем, что все поля заполнены
            if ($username && $password) {
                // Проверяем, существует ли пользователь с таким именем
                $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
                $stmt->execute(['username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Проверяем, совпадает ли пароль
                    if (password_verify($password, $user['password'])) {
                        // Сохраняем данные о пользователе в сессию
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        echo json_encode(['success' => true, 'message' => 'Вход успешен', 'user' => $user['username']]);
                    } else {
                        echo json_encode(['error' => 'Неверный пароль']);
                    }
                } else {
                    // Пользователь с таким именем не найден
                    echo json_encode(['error' => 'Пользователь не найден']);
                }
            } else {
                echo json_encode(['error' => 'Заполните все поля']);
            }
        }
    }
?>
