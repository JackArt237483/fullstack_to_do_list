<?php

namespace User\Block\Middleware;
  class AuthMiddleware{
      public static function check():void{
          if(!isset($_SESSION['user_id'])){
              header('Location: index.php?action=login');
              exit();
          }
      }
  }
  //функция проверки есть ли тут поль
?>