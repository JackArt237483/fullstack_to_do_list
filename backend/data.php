<?php
    include "db.php";
    global $db;
    header('Content-type: application/json');
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $action = $_POST['action'];

        if($action === 'register'){
            registerUser($db);
        } elseif($action === 'login') {
            loginUser($db);
        } else{
            echo json_encode(['error' => 'Непрвильное действие']);
        }
        exit();
    }
    function registerUser($db){
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $email = $_POST['email'] ?? null;
        // проверка на заполенные поля если они заполенены то происходит проверка
        if($username && $password && $phone && $email){
            $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->excute(['email' => $email]);
            // если найден похожий email в то пишет пользватель уже найден
            if($stmt->rowCount() > 0){
                echo json_encode(['error' => 'Пользователь уже есть где надо']);
                return;
            }
            $hashedPassword  = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO users (username,password,phone,email) VALUES (:username,:password,:phone,:email)');
            $stmt->excute(['username'=> $username,'password'=>$hashedPassword,'phone'=>$phone,'email'=>$email]);
            echo json_encode(['success' => true,'message' => 'Регистрция пользователя успешна']);
        }else{
            echo json_encode(['error' => 'Поля не все заполенны']);
        }
    }
    function loginUser($db){
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if($username && $password){
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
            $db->excute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])){
                echo json_encode(['success'=> true,'message'=>'все поля заполнены правильно','user'=>$user['username']]);
            } else {
                echo json_encode(['error' => 'Ошибка логина и пароля']);
            }
        } else {
            echo json_encode(['error'=> 'где то ошибка произошла']);
        }
    }
?>