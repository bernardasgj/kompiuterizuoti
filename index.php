<?php

use Core\Router;

spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    $file = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/core/Attributes/Route.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Repository.php';
require_once __DIR__ . '/core/QueryBuilder.php';

$router = new Router();
$router->dispatch();
