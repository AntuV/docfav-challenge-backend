<?php

require __DIR__ . "/vendor/autoload.php";

$method = strtolower($_SERVER['REQUEST_METHOD']);
$controller = new App\Controllers\UserController();
$controller->{$method}();
