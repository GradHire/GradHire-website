<?php

namespace app\core\db;

class Configuration
{
    static private array $databaseConfiguration = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'hirchytsd',
        'port' => '3316',
        'login' => 'hirchytsd',
        'password' => '20032002'
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