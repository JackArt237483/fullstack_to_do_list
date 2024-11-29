<?php

namespace User\Block\Controllers;

use User\Block\Models\User;
use User\Block\Interfaces\UserRepositoryInterface;
use User\Block\Repositories\UserRepository;

class UserController {
    private $userModel;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userRepository->findByEmail($email);

            // Проверка, найден ли пользователь и совпадает ли пароль
            if ($user && $password($password,$user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php?action=todos'); // Переход на страницу задач
                exit();
            } else {
                echo 'Invalid email or password!';
            }
        }
        include __DIR__ . '../../Views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];

            $user = new User($username, $email, $password, $phone);

            if ($this->userRepository->save($user)) {
                header('Location: index.php?action=login'); // Переход на страницу логина
                exit;
            } else {
                echo 'Registration failed!';
            }
        }
        include __DIR__ . '../../Views/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login'); // Переход на страницу логина
        exit;
    }
}
