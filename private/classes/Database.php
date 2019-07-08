<?php

class Database {
    private static $instance = null;
    private $conn;
    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $options=[
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES  => false
        ];
        $this->conn = new PDO($dsn, DB_USER, DB_PASS,$options);
    }
    private function __clone() {
        throw new Exception("Nope");
    }
    private function __wakeup() {
        return false;
    }
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getConnection() {
        return $this->conn;
    }
}