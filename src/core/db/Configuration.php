<?php

namespace app\src\core\db;

class Configuration
{
    static private array $databaseConfiguration = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'gozzog',
        'port' => '3316',
        'login' => 'gozzog',
        'password' => '03032003'
    );

    static public function getLogin(): string
    {
        return self::$databaseConfiguration['login'];
    }

    static public function getHostname(): string
    {
        return self::$databaseConfiguration['hostname'];
    }

    static public function getDatabase(): string
    {
        return self::$databaseConfiguration['database'];
    }

    static public function getPassword(): string
    {
        return self::$databaseConfiguration['password'];
    }

    static public function getPort(): string
    {
        return self::$databaseConfiguration['port'];
    }
}