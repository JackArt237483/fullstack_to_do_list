<?php
namespace User\Block\Services;

class Router {
    private array $routes = [];

    public function addRoute(string $method, string $path, callable $handler): void {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void {
        $method = strtoupper($method);

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $requestData = array_merge($_GET, $_POST);

            $arguments = [];

            // Определяем параметры вызываемого обработчика
            if (is_array($handler)) {
                // Это метод класса

                $reflection = new \ReflectionMethod($handler[0], $handler[1]);
            } else {
                // Это функция или Closure
                $reflection = new \ReflectionFunction($handler);
            }

            foreach ($reflection->getParameters() as $param) {
                $name = $param->getName();
                $arguments[] = $requestData[$name] ?? null;
            }

            call_user_func_array($handler, $arguments);
        } else {
            http_response_code(404);
            echo '404 - Not Found';
        }
    }
}

?>