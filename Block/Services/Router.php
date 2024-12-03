<?php
namespace User\Block\Services;

class Router {
    private array $routes = [];

    public function addRoute(string $method, string $path, callable $handler): void {
        $this->routes[strtoupper($method)][] = [
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(string $method, string $path): void {
        $method = strtoupper($method);

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
                $pattern = "#^$pattern$#";

                if (preg_match($pattern, $path, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $params = array_merge($params, $_GET, $_POST); // Передача всех параметров
                    call_user_func_array($route['handler'], [$params]);
                    return;
                }
            }
        }

        http_response_code(404);
        echo 'Not Found';
    }
}
?>
