<?php

namespace User\Block\Controllers;

use User\Block\Interfaces\RouteConfigurable;
use User\Block\Interfaces\UserRepositoryInterface;
use User\Block\Services\Router;
use User\Block\Models\Users;

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

    public function login(array $params): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $params['email'] ?? null;
            $password = $params['password'] ?? null;

            $user = $this->userRepository->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php?action=todos');
                exit();
            } else {
                echo 'Invalid email or password!';
            }
        }
        include __DIR__ . '/../Views/login.php';
    }

    public function register(array $params): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $params['username'] ?? null;
            $email = $params['email'] ?? null;
            $phone = $params['phone'] ?? null;
            $password = $params['password'] ?? null;

            $user = new Users($username, $email, $phone, $password);

            if ($this->userRepository->save($user)) {
                header('Location: index.php?action=login');
                exit;
            } else {
                echo 'Registration failed!';
            }
        }
        include __DIR__ . '/../Views/register.php';
    }

    public function logout(array $params): void {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
?>
