<?php
/** @var $exception \Exception */
$code = $exception->getCode();
if ($code === 404) {
    require_once __DIR__ . '/errors/error404.php';
} elseif ($code === 500) {
    require_once __DIR__ . '/errors/error500.php';
}
else {
    echo $exception->getCode();
    echo '<br>';
    echo $exception->getMessage();
}
?>
