<?php

namespace app\src\model;

use app\src\core\db\DbModel;

abstract class UserModel extends DbModel
{
    public $status;
    public $created_at;

    abstract public function getDisplayName(): string;
}