<?php

namespace User\Block\Interfaces;
       interface TodoRepositoryInterface{
        public function getAllByUserId(int $userId):array;
        public function create(array $data):void;
        public function update(int $id, array $data):void;
        public function delete(int $id):void;
        public function getById(int $id): ?array;
   }
   // ОПИСЫВАЕТ МЕТОДЫ ДЛЯ РАБОТЫ В РЕПОЗИТОРИЯХ
?>