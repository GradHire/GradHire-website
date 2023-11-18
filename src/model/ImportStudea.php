<?php

namespace app\src\model;

use app\src\core\db\Database;

class ImportStudea
{
    private $db;

    public function __construct()
    {
        $this->db = Database::get_conn();
    }

    public function importerligneStudea($row)
    {
        echo "test";
    }
}