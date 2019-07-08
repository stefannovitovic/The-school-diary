<?php
define("DB_HOST", "localhost");
define("DB_NAME", "e_dnevnik_");
define("DB_USER", "root");
define("DB_PASS", "");
class Db {
    private static $instance = null;
    private $conn;
    public static $type;
    private function __construct() {

        if(!in_array(self::$type,PDO::getAvailableDrivers())) {
            throw new Exception("Driver doesn't exist");
        }

        switch (self::$type) {
            case 'mysql':
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                break;
            case 'mssql':
                $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=". DB_NAME;
                break;
            case 'oracle':
                $dsn = "oci:dbname=" . DB_HOST;
                break;
            case 'postgresql':
                $dsn = "pgsql:host=" . DB_HOST . ";port=5432;dbname=" . DB_NAME;
                break;
            default:
                throw new Exception("Database connection not available");
        }

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
    public static function getInstance($type) {
        if(isset(self::$type)) {
            throw new Exception("Different connection started");
        }
        self::$type = $type;
        if(!self::$instance) {
            self::$instance = new Db();
        }
        //proveri da li je konekcija na trazen tip
        return self::$instance;
    }
    public function getConnection() {
        return $this->conn;
    }
}
try {
    $db = Db::getInstance('mysql')->getConnection();
} catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}

try {
    $db = Db::getInstance('oracle')->getConnection();
} catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}