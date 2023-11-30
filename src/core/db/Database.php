<?php

namespace app\src\core\db;

use app\src\core\exception\ServerErrorException;
use PDO;
use PDOException;

class Database
{
    private static PDO $connexion;

    public static function convertArrayToPostgres($phpArray)
    {
        $escapedArray = array_map(function ($value) {
            return self::get_conn()->quote($value);
        }, $phpArray);
        return '{' . implode(',', $escapedArray) . '}';
    }

    /**
     * @throws ServerErrorException
     */
    public static function get_conn(): PDO
    {
        if (!isset(self::$connexion)) {
            try {
                $dsn = "pgsql:host=" . DB_HOSTNAME . ";dbname=" . DB_DB . ";port=" . DB_PORT;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true
                ];
                self::$connexion = new PDO($dsn, DB_LOGIN, DB_PASSWORD, $options);
                self::$connexion->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            } catch (PDOException) {
                throw new ServerErrorException();
            }
        }
        return self::$connexion;
    }

    /**
     * @throws ServerErrorException
     */
    public function prepare($sql): \PDOStatement
    {
        return self::get_conn()->prepare($sql);
    }
}