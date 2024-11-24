<?php

namespace User\Block\Controllers;

use User\Block\Models\Todo;

class TodoController
{
    public function index()
    {
        // Получение данных из модели
        $todos = Todo::all() ?? [];
        require_once __DIR__ . '../../Views/index.php';
    }

    public function store($title)
    {
        Todo::create(['title' => $title]);
        $this->index();
    }

    public function destroy($id)
    {
        Todo::delete($id);

        $this->index();
    }

    public function complete($id)
    {
        Todo::update($id, ['is_completed' => 1]);

        $this->index();
    }

    public function update($id, $title)
    {
        Todo::update($id, ['title' => $title]);

        $this->index();
    }
}
