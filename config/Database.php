<?php
namespace Config;

use PDO;

class Database
{
    
    private static $instance = null;

    /**
     * Get instance PDO function
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                $dbName = getenv('DB_DATABASE');
                $host = getenv('DB_HOST');
                $port = getenv('DB_PORT');
                $user = getenv('DB_USERNAME');
                $pass = getenv('DB_PASSWORD');
                $driver = getenv('DB_CONNECTION');
                self::$instance = new PDO("$driver:host=$host;port=$port;dbname=$dbName", $user, $pass);
                self::$instance->exec("SET NAMES 'utf8'");
            } catch (PDOException $ex) {
                die($ex->getMessage());
            }
        }
        return self::$instance;
    }
}
