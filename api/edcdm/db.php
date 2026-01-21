<?php
$config = require __DIR__ . '/config.php';

class DB {
    private static $pdo = null;

    public static function get()
    {
        if (self::$pdo === null) {
            $c = $GLOBALS['config_db'] ?? $config['db'];
            $dsn = "mysql:host={$c['host']};dbname={$c['dbname']};charset=utf8mb4";
            try {
                self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'DB connection failed', 'message' => $e->getMessage()]);
                exit;
            }
        }
        return self::$pdo;
    }
}
