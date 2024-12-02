<?php
    namespace User\Block\Services;


    use PDO;
    use User\Block\Interfaces\DataBaseInterface;

    class DatabaseService implements DataBaseInterface {
        private PDO $pdo;
        public function __construct(){
            $dbPath = __DIR__ . '/../../config/identifier.sqlite';
            $this->pdo = new PDO('sqlite:' . $dbPath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        public function query(string $sql, array $params = []): array{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }
        public function execute(string $sql, array $params = []): void{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        }
    }
?>