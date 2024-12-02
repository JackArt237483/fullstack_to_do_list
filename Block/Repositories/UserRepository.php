<?php
    namespace User\Block\Repositories;
    use User\Block\Interfaces\UserRepositoryInterface;
    use User\Block\Models\Users;
    use User\Block\Services\DatabaseService;

    class UserRepository implements UserRepositoryInterface {
        private DatabaseService $db;
        public function __construct(DatabaseService $db) {
            $this->db = $db;
        }
        public function findByEmail(string $email): ?array {
            $result = $this->db->query('SELECT * FROM users WHERE email = :email', ['email' => $email]);
            return $result[0] ?? null;
        }
        public function save(Users $user): bool {
            $data = [
                'username' => $user->getUserName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'password' => $user->getPassword()
            ];
            // Пример SQL-запроса для вставки нового пользователя
            return $this->db->execute(
                'INSERT INTO users (username, email, phone, password) VALUES (:username, :email, :phone, :password)',
                $data
            );
        }

    }
