<?php

namespace Tero\Services;

class RoutingService {
    private $routes = [];
    
    public function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch($requestMethod, $requestUri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $requestUri) {
                return call_user_func($route['callback']);
            }
        }
        return $this->notFound();
    }

    private function notFound() {
        http_response_code(404);
        return '404 Not Found';
    }
}