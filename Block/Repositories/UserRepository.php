<?php
    namespace User\Block\Repositories;
    use PDO;
    use User\Block\Interfaces\UserRepositoryInterface;
    use User\Block\Models\Users;

    class UserRepository implements UserRepositoryInterface{
        private PDO $pdo;
        // использовать подклбчение только один раз
        public function __construct(PDO $pdo){
            $this->pdo = $pdo;
        }
        public function findByEmail(string $email): ?array{

            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // данные в виде масива котоыре возращаются
            return $user ?: null;
        }
        public function save(Users $user): bool{
            $stmt = $this->pdo->prepare('INSERT INTO users (username,email,password,phone) values (:username,:email,:password,:phone)');

            return $stmt->execute([
                'username' => $user->getUserName(),
                'email' => $user->getEmail(),
                'phone' => $user->getUserName(),
                'password' => $user->getPassword()
            ]);
        }
    }
?>