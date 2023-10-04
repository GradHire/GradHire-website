<?php

namespace app\src\core\db;

use app\src\core\exception\ServerErrorException;
use PDO;
use PDOException;

class Database
{
    private static PDO $connexion;

    /**
     * @throws ServerErrorException
     */
    public function prepare($sql): \PDOStatement
    {
        return self::get_conn()->prepare($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public static function get_conn(): PDO
    {
        if (!isset(self::$connexion)) {
            try {
                $dsn = "mysql:host=" . db_hostname . ";dbname=" . db_database . ";port=" . db_port;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                self::$connexion = new PDO($dsn, db_login, db_password, $options);
            } catch (PDOException) {
                throw new ServerErrorException();
            }
        }

        return self::$connexion;
    }
}