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
        $router->addRoute('GET', '/account', [$this, 'account']);
        $router->addRoute('POST','/update_profile', [$this, 'updateProfile']);
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
        session_destroy(); // Удаляем сессию
        header('Location: index.php?action=login'); // Перенаправляем на страницу входа
        exit;
    }
    public function account() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login'); // Если пользователь не авторизован
            exit();
        }
        // Получаем данные пользователя
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->findById($userId);
        $roles = $_SESSION['roles'] ?? [];


        // Подключаем шаблон личного кабинета
        include __DIR__ . '../../Views/account.php';
    }
    public function updateProfile(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userId = $_SESSION['user_id'] ?? null;

            if(!$userId){
                echo 'Юзера нету мужик';
                return;
            }

            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $password = $_POST['password'] ?? null;

            if($password ===  null || trim($password) === " "){
                $user = $this->userRepository->findById($userId);
                $password = $user['password'];
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
            }

            $user = new Users($username, $email, $phone, $password);
            $user->setId($userId);



            if($this->userRepository->updateUser($user)){
                echo 'Update profile';
                exit();
            } else {
                echo 'Update failed bro';
            }
        }
        else {
            echo 'this methos is not true';
        }
        // Подключаем шаблон личного кабинета
        include __DIR__ . '../../Views/account.php';
    }

}
?>