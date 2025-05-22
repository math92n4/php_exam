<?php


class Database extends DBCredentials {

    protected ?PDO $pdo;
    
    public function __construct() {

    $dsn = 'mysql:host=' . self::host . ':' . self::port . ';dbname=' . self::dbname . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $this->pdo = new PDO($dsn, self::user, self::password, $options);
    }

    public function connect() {
        return $this->pdo;
    }

    public function __destruct() {
        $this->pdo = null;
    }
}