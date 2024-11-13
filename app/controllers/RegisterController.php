<?php
    use Controller;
    class RegisterController extends Controller {
        public function showRegisterForm(){
            $this->view('register');
        }

        // Функция для регистрации пользователя
        public function register($data): void{
            // Получаем данные из POST-запроса
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            $phone = $data['phone'] ?? null;
            $email = $data['email'] ?? null;

            // Проверяем, что все данные заполнены
            if ($username && $password && $phone && $email) {
                // Проверяем, существует ли уже пользователь с таким email
                $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
                $stmt->execute(['email' => $email]);

                // Если пользователь с таким email уже существует
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['error' => 'Пользователь с таким email уже существует']);
                    return;
                }

                // Хэшируем пароль
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Вставляем нового пользователя в базу данных
                $stmt = $this->db->prepare('INSERT INTO users (username, password, phone, email) VALUES (:username, :password, :phone, :email)');
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

    }
?>