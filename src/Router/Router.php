<?php

namespace App\Router;

use App\Middlewares\Middlewares;

class Router{

    private array $routes = [];

    public function __construct(private Middlewares $middlewares){}

    public function load(array $routes): void {
        foreach($routes as $route){
            $this->routes[] = [
                'method' => $route[0],
                'path' => $route[1],
                'handler' => $route[2],
                'middlewares' => $route[3] ?? [],
            ];
        }
    }

    public function dispatch(string $method, string $uri): string {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        foreach($this->routes as $route){
            if($route['method'] !== $method || $route['path'] !== $path){
                continue;
            }       

            foreach($route['middlewares'] as $name){
                $this->middlewares->run($name);
            }

            [$class, $action] = $route['handler'];
            return (new $class())->$action();
        }

        http_response_code(404);
        return json_encode(['message' => 'Route not found', 'responseCode' => 404], JSON_UNESCAPED_UNICODE);
     }
}