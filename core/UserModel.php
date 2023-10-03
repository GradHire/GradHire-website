<?php

namespace app\core;

use app\core\db\DbModel;

abstract class UserModel extends DbModel
{
    public $status;
    public $created_at;

    abstract public function getDisplayName(): string;
}