<?php
include 'db.php';
global $db;

class User {
    private $db;
    private $user_id;

    public function __construct($db){
        $this->db = $db;
        // Получаем ID пользователя из сессии (если он существует)
        $this->user_id = $_SESSION['user_id'] ?? null;
    }


    // Функция для обновления данных в систему
    public function update($data): void {
        // Проверяем, что пользователь авторизован
        if (!$this->user_id) {
            echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
            return;
        }

        // Подготавливаем обновления
        $updates = [];
        $params = ['id' => $this->user_id];

        // Проверяем, что переданы данные для обновления
        if (!empty($data['username'])) {
            $updates[] = 'username = :username';
            $params['username'] = $data['username'];
        }
        if (!empty($data['email'])) {
            $updates[] = 'email = :email';
            $params['email'] = $data['email'];
        }
        if (!empty($data['phone'])) {
            $updates[] = 'phone = :phone';
            $params['phone'] = $data['phone'];
        }
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $updates[] = 'password = :password';
            $params['password'] = $hashedPassword;
        }

        // Если есть данные для обновления, выполняем SQL-запрос
        if (!empty($updates)) {
            try {
                // Формируем SQL-запрос для обновления
                $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = :id';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);

                // Возвращаем успешный ответ
                echo json_encode(['success' => true, 'message' => 'Данные обновлены']);
            } catch (Exception $e) {
                // В случае ошибки
                echo json_encode(['success' => false, 'error' => 'Ошибка обновления: ' . $e->getMessage()]);
            }
        } else {
            // Если нет данных для обновления
            echo json_encode(['success' => false, 'message' => 'Нет данных для обновления']);
        }
    }

}

// Создаем объект пользователя
$user = new User($db);

// Чтение данных из входящего запроса
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? null;

// Обработка различных действий
switch ($action) {
    case "register":
        $user->register($data);
        break;
    case "login":
        $user->login($data);
        break;
    case "update":
        $user->update($data);
        break;
    default:
        echo json_encode(['error' => 'Неверное действие']);
}
?>
