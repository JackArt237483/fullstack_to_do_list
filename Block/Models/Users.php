<?php

namespace User\Block\Models;
// класс который отвечает за отправку данных
class Users{
    private string $username;
    private string $email;
    private string $phone;
    private string $password;
    private array $roles; //Хранит массив ролей пользователей
    public function __construct(string $username,string $email,string $phone,string $password,array $roles = []){
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = password_hash($password,PASSWORD_DEFAULT);
        $this->roles = $roles;
    }
    public function getUserName():string{
        return $this->username;
    }
    public function getEmail():string{

        return $this->email;
    }
    public function getPhone():string{
        return $this->phone;
    }
    public function getPassword():string{
        return $this->password;
    }
    public function getRoles(): array{
        return $this->roles;
    }
    public function setRoles(array $roles): array{
        $this->roles = $roles; // функция для хранения ролей пользователся
    }

}
?>