<?php

use App\Router\Router;
use App\Middlewares\Middlewares;
use App\Exceptions\ExceptionSerialize;

require __DIR__ . ('/../vendor/autoload.php');

session_start();
header('Content-Type: application/json; charset=utf-8');

$router = new Router(new Middlewares());
$router->load(require __DIR__ . '/../config/routes.php');

try{
    echo $router->dispatch(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']        
    );
}catch(\throwable $e){
    $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
    http_response_code($code);
    echo json_encode(
        (new ExceptionSerialize())->serializeException(
            $e->getMessage(),
            $code), JSON_UNESCAPED_UNICODE
    );
}
