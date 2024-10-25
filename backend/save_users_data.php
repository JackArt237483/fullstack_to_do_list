<?php
    session_start();//запускается сессия где есть пользователь
    include 'db.php';
    global $db;
    header('Content-type: application/json');
    // Проверка юзера есть ли он в у нас в сесии
    if(!isset($_SESSION['user_id'])){
        echo json_encode(['success'=> false, 'error' => 'Юзер не найден']);
        exit();
    }

    $user_id  = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'),true);
    $email = $data['email'] ?? null;
    $phone = $data['phone'] ?? null;
    $password = $data['password'] ?? null;

    $updates = [];
    $params = ['id' => $user_id]; // МАССИВ PHP ДЛЯ ПОДГОТОВКИ ЗАПРОСА

   if($email) {
       $updates[] = 'email = :email';
       $params['email'] = $email;
   }

   if($phone) {
       $updates[] = 'phone = :phone';
       $params['phone'] = $phone;
   }

   if($password) {
       $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
       $updates[] = 'password = :password';
       $params['password'] = $hashedPassword;

   }

    if(!empty($updates)){
        $sql = 'UPDATE users SET ' . implode(',',$updates) . 'WHERE id = : id';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true,'error'=>'Данные успешно обновленны']);
    } else {
        echo json_encode(['success' => false,'error'=>'Нет данных для обновления']);
    }
?>