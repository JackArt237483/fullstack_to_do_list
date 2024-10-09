<?php
    try {
        $db = new PDO("sqlite:" . __DIR__ . "/identifier.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e) {
        echo "Ошибка подключения " . $e->getMessage();
    }
?>
