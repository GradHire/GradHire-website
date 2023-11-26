<?php

use app\src\core\components\Lien;
use app\src\core\components\Separator;
use app\src\model\Application;

$currentTab = $_COOKIE['currentTab'] ?? 'tab1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tab'])) {
        $currentTab = $_POST['tab'];
        setcookie('currentTab', $currentTab, time() + (86400 * 30), '/');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>
