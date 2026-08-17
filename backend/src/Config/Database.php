<?php

namespace App\Config;

use App\Helpers\Env;
use App\Helpers\Error;
use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $driver = Env::get_env_var('DATABASE_DRIVER');
        $host = Env::get_env_var('DATABASE_HOST');
        $db = Env::get_env_var('DATABASE_NAME');
        $user = Env::get_env_var('DATABASE_USER');
        $pass = Env::get_env_var('DATABASE_PASSWORD');
        $charset = "utf8mb4";

        $dsn = "$driver:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);

            $this->connection->exec("SET time_zone = '+02:00'");
        } catch (PDOException $e) {
            Error::sendError($e->getMessage(), 500);
        }
    }

    public function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Un Singleton ne peux pas être désérialisé.");
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // accesseur lecture
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
