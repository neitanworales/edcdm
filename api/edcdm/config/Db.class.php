<?php

class Db
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        require 'Conf.class.php';
        $conf = Conf::getInstance();
        $host = $conf->getHostDB();
        $dbname = $conf->getDB();
        $username = $conf->getUserDB();
        $password = $conf->getPassDB();

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error en la conexión: ' . $e->getMessage());
        }
    }

    // Singleton
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    // Método execute()
    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function executeWithId($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        if (stripos(trim($sql), 'insert') === 0) {
            return $this->pdo->lastInsertId();
        }

        return $stmt;
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>