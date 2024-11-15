<?php
    namespace App\Controllers;

    use App\Models\UserModel;
    use App\Core\Controller;

    class RegisterController extends Controller {
        private $userModel;
        public function __construct($db)
        {
            $this->userModel = new UserModel($db);
        }
        public function showRegisterForm(){
            $this->view('register');
        }
        // Функция для регистрации пользователя
        public function register($data) {
            // Получаем данные из POST-запроса
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            $phone = $data['phone'] ?? null;
            $email = $data['email'] ?? null;
            // Проверяем, что все данные заполнены
            if ($username && $password && $phone && $email) {

                if($this->userModel->userExiets($email)){
                    echo json_encode(['error' => 'Пользователь не найден с таким Email']);
                }

                $registerData = $this->userModel->registerUser($username,$password,$phone,$email);
                if($registerData){
                    echo json_encode(['success' => true, 'message' => 'Регистрация успешна']);
                } else {
                    echo json_encode(['error' => 'Регистрация не пройдена получается']);
                }
            }
            else {
                // Если не все поля заполнены
                echo json_encode(['error' => 'Заполните все поля']);
            }
        }
    }
?>