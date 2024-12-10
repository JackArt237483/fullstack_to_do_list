<?php
    namespace User\Block\Interfaces;

    use User\Block\Models\Users;
    interface UserRepositoryInterface{
        public function findByEmail(string $email): ?array;
        public function save(Users $user):bool;
        public function assignRoles(int $userId, array $roleIds):bool; // выбор роли пользователя по массиву
        public function getUserRoles(int $userId): array; // получает массив пользователей
        public function findById(int $id): ?array ;
        public function updateUser(Users $user): bool;
    }
?>