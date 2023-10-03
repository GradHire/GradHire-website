<?php

namespace app\src\core\db;


use PDO;

class Database
{
    public PDO $pdo;

    public function __construct()
    {
        $hostname = Configuration::getHostname();
        $databaseName = Configuration::getDatabase();
        $login = Configuration::getLogin();
        $password = Configuration::getPassword();
        $port = Configuration::getPort();
        $this->pdo = new PDO("mysql:host=$hostname;port=$port;dbname=$databaseName", $login, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

}