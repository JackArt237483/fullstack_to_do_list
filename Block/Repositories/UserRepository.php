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
        $this->db->execute(
            'INSERT INTO users (username, email, phone, password) VALUES (:username, :email, :phone, :password)',
            $data
        );

        $userId = $this->db->query('SELECT last_insert_rowid() AS id')[0]['id']; // Получаем ID нового пользователя

        // Привязываем роли, если указаны
        if (!empty($user->getRoles())) {
            return $this->assignRoles($userId, $user->getRoles());
        }

        return true;
    }
    public function assignRoles(int $userId, array $roleIds): bool {
        foreach ($roleIds as $roleId) {
            $this->db->execute(
                'INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)',
                ['user_id' => $userId, 'role_id' => $roleId]
            );
        }
        return true;
    }
    public function getUserRoles(int $userId): array {
        $result = $this->db->query(
            'SELECT r.name FROM roles r
             JOIN user_roles ur ON r.id = ur.role_id
             WHERE ur.user_id = :user_id',
            ['user_id' => $userId]
        );

        return array_column($result, 'name');
    }
    public function findById(int $id): ?array {
        $result = $this->db->query('SELECT * FROM users WHERE id = :id', ['id' => $id]);
        return $result[0] ?? null;
    }
    public function updateUser(Users $user): bool{
        return $this->db->execute(
            "UPDATE users SET 
                 username = :username, 
                 email = :email, 
                 phone = :phone, 
                 password = :password
                WHERE id = :id"
            ,[
               ':username' => $user->getUserName(),
               ':email' => $user->getEmail(),
               ':phone' => $user->getPhone(),
               ':password' => $user->getPassword(),
                ':id' => $user->getId()
        ]);
    }
}

?>
