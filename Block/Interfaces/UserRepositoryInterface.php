<?php
    namespace User\Block\Interfaces;

    use User\Block\Models\Users;
    interface UserRepositoryInterface{
        public function findByEmail(string $email): ?array;
        public function save(Users $user):bool;
    }
?>