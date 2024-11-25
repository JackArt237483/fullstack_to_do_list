<?php

namespace User\Block\Controllers;

use User\Block\Models\User;

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);
            if ($user) {
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

            if ($this->userModel->register($username, $email, $password, $phone)) {
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
