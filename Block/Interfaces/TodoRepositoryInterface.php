<?php
   interface TodoRepositoryInterface{
        public function getAllBuUserId(int $userId):array;
        public function create(array $data):void;
        public function update(int $id, array $data):void;
        public function delete(int $id):void;
        public function detById(int $id): ?array;
   }
   // ОПИСЫВАЕТ МЕТОДЫ ДЛЯ РАБОТЫ В РЕПОЗИТОРИЯХ
?>