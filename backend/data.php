<?php
    include 'db.php';
    global $db;
    class User
    {
        private $db;
        private $user_id;

        public function __construct($db)
        {
            $this->db = $db;
            $this->user_id = $_SESSION['user_id'] ?? null;
        }

        public function register($data)
        {
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            $phone = $data['phone'] ?? null;
            $email = $data['email'] ?? null;

            if ($username && $password && $phone && $email) {
                $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
                $stmt->execute(['email' => $email]);

                if ($stmt->rowCount() > 0) {
                    return ['error' => 'Пользователь с таким email уже существует'];
                }

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare('INSERT INTO users (username, password, phone, email) VALUES (:username, :password, :phone, :email)');
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashedPassword,
                    'phone' => $phone,
                    'email' => $email
                ]);
                return ['success' => true, 'message' => 'Регистрация успешна'];
            }

            return ['error' => 'Заполните все поля'];
        }

        public function login($data)
        {
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;

            if ($username && $password) {
                $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
                $stmt->execute(['username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    return ['success' => true, 'message' => 'Вход успешен', 'user' => $user['username']];
                }

                return ['error' => 'Неверный пароль или пользователь не найден'];
            }

            return ['error' => 'Заполните все поля'];
        }

        public function update($data)
        {
            if (!$this->user_id) {
                return ['error' => 'Пользователь не авторизован'];
            }

            $updates = [];
            $params = ['id' => $this->user_id];

            if (!empty($data['username'])) {
                $updates[] = 'username = :username';
                $params['username'] = $data['username'];
            }

            if (!empty($data['email'])) {
                $updates[] = 'email = :email';
                $params['email'] = $data['email'];
            }

            if (!empty($data['phone'])) {
                $updates[] = 'phone = :phone';
                $params['phone'] = $data['phone'];
            }

            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $updates[] = 'password = :password';
                $params['password'] = $hashedPassword;
            }

            if ($updates) {
                $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = :id';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                return ['success' => true, 'message' => 'Данные обновлены'];
            }

            return ['error' => 'Нет данных для обновления'];
        }
    }
?>