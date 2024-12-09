<?php

namespace User\Block\Controllers;

use User\Block\Models\Users;
use User\Block\Interfaces\UserRepositoryInterface;
use User\Block\Interfaces\RouteConfigurable;
use User\Block\Services\Router;

class UserController implements RouteConfigurable {
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function registerRoutes(Router $router): void {
        $router->addRoute('GET', '/login', [$this, 'login']);
        $router->addRoute('POST', '/login', [$this, 'login']);
        $router->addRoute('GET', '/register', [$this, 'register']);
        $router->addRoute('POST', '/register', [$this, 'register']);
        $router->addRoute('GET', '/logout', [$this, 'logout']);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userRepository->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['roles'] = $this->userRepository->getUserRoles($user['id']); // Сохраняем роли в сессии
                header('Location: index.php?action=todos');
                exit();
            } else {
                echo 'Invalid email or password!';
            }
        }
        include __DIR__ . '/../Views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $roles = $_POST['roles'] ?? []; // Получаем роли из формы

            $user = new Users($username, $email, $phone, $password, $roles);

            if ($this->userRepository->save($user)) {
                header('Location: index.php?action=login');
                exit();
            } else {
                echo 'Registration failed!';
            }
        }
        include __DIR__ . '/../Views/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}

?>