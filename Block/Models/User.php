<?php

namespace User\Block\Models;

use PDO;
class User{
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function login($email,$password){
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // данные в виде масива котоыре возращаются

        if($user && password_verify($password,$user['password'])){
            return $user;
        }

        return false;
    }
    public function register($username,$email,$password,$phone){
        $stmt = $this->pdo->prepare('INSERT INTO users (username,email,password,phone) values (:username,:email,:password,:phone)');

        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}