<?php

    namespace App\Core;
    global $db;

    use PDO;
    class Controller {
        protected $db;
        public function __construct($db)
        {
            $this->db = $db;
        }
        public function view($view, $data = []){
            extract($data); //преобразование в переменную

            $viewFile = __DIR__ . '/../Views/' . $view . '.php';

            if (file_exists($viewFile)) {
                require $viewFile;
            } else {
                die("Представление {$view} не найдено");
            }
        }

    }
?>