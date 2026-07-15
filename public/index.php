<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/app.php';

$request = new Core\Request();
$response = new Core\Response();
$router = new Core\Router($request, $response);

require_once __DIR__ . '/../routes/api.php';

try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => "Error interno del servidor"
    ]);
}