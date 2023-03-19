<?php

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$request = file_get_contents('php://input');

if (empty($request)) {
    $request = [];
}

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);

use Routes\Router;

$router = new Router();
$response = $router->route($uri, $method, $request);

echo $response;
