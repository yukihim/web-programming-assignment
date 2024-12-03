<?php
class Database {
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "medical_appointment";
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli(self::$host, self::$username, self::$password, self::$dbname);
            if (self::$connection->connect_error) {
                die("Database connection failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}
?>
