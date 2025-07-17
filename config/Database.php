<?php

namespace Config;

use PDO;

class Database
{
    private static $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/db_config.php';

            $host = $config['HOST'];
            $db = $config['DB'];
            $user = $config['USER'];
            $pass = $config['PASS'];

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            self::$pdo = new PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        echo "Connected successfully to the database.\n";
        return self::$pdo;
    }
}
