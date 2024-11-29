<?php

    interface DataBaseInterface{
        //возращает данные первый метод
        public function query(string $sql, array $params=[]): array;
        // не возращает данные второй метод
        public function execute(string $sql, array $params=[]): void;
        // методы которые будут использоватся с базой данных
    }
    // создания класса более гибкого и легко расширяемого
    ?>
