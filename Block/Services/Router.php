<?php
    namespace User\Block\Services;
    class Router {
        private array $routes = [];
        public function addRoute(string $method,string $path,callable $handler):void{
            $this->routes[strtoupper($method)][$path] = $handler;
        }
        public function dispatch(string $method,string $path){
            $method = strtoupper($method);

            if(isset($this->routes[$method][$path])){
                call_user_func($this->routes[$method][$path]);
            } else {
                http_response_code(404);
                echo 'This method lost bro';
            }
        }
    }
?>