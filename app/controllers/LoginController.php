<?php

    namespace App\Controller;
    use App\Models\UserModel;
    use App\Core\Controller;


    class LoginController extends Controller{
        public $userModel;
        public function __construct($db)
        {
            $this->userModel = new UserModel($db);
        }
        public function showLoginForm(){
            $this->view('login');
        }
        // Функция для входа в систему
        public function login($data): void{
            // Получаем данные из POST-запроса
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            // Проверяем, что все поля заполнены
            if ($username && $password) {
                // проверка данных пользователя и та кдалее
                $user = $this->userModel->verifyCredentials($username,$password);
                if ($user) {
                    // Сохраняем данные о пользователе в сессию
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // Перенаправляем на страницу задач
                    header('Location: /tasks');
                    exit();
                } else {
                    // Пользователь с таким именем не найден
                    $this->view('login', ['error' => 'Что не то для всего имя и пароль неправильный']);
                }
            } else {
                $this->view('login', ['error' => 'Логин не правильный введен']);
            }
        }
    }
?>
