<?php
/** @var $user User */

use app\src\model\Users\Roles;
use app\src\model\Users\User;

$this->title = 'Profile';
if ($user->role() === Roles::Enterprise)
    require __DIR__ . "/profile/enterprise.php";
else require __DIR__ . "/profile/others.php";
?>
