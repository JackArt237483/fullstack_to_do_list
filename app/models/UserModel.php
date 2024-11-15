<?php
    namespace App\Models;
    global $db;
    use PDO;
    class userModel {
        private $db;
        public function __construct($db)
        {
            $this->db = $db;
        }
        public function verifyCredentials($username,$password)
        {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->excute(['username' => $username]);
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return false;
        }
        public function userExiets($email)
        {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            return $stmt->rowCount() > 0;
        }
        public function registerUser($username,$password,$phone,$email){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Вставляем нового пользователя в базу данных
            $stmt = $this->db->prepare('INSERT INTO users (username, password, phone, email) VALUES (:username, :password, :phone, :email)');
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'phone' => $phone,
                'email' => $email,
            ]);
            // Возвращаем успешный ответ
            return true;
        }

    }
?>