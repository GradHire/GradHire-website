<?php
/** @var $this \thecodeholic\phpmvc\View */

use app\src\model\Application;

$this->title = 'Profile';
$user = Application::getUser();
if (is_null($user)) Application::go_home();
?>

<div>

    <h1>Profile page</h1>
    <p><?= $user->full_name() ?></p>
    <p><?= $user->attributes()["emailutilisateur"] ?></p>
    <p><?= $user->role() ?></p>
</div>