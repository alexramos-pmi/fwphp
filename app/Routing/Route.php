<?php

namespace App\Routing;

use App\Http\Kernel;
use App\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;

class Route
{
    protected static array $routes = [];
    protected static array $currentMiddlewareGroup = [];

    public static function get(string $uri, $action): void
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, $action): void
    {
        self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, $action): void
    {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete(string $uri, $action): void
    {
        self::addRoute('DELETE', $uri, $action);
    }

    protected static function addRoute(string $method, string $uri, $action): void
    {
        self::$routes[$method][$uri] = [
            'uri' => $uri,
            'action' => $action,
            'middleware' => self::$currentMiddlewareGroup // pega o middleware do grupo ativo
        ];
    }

    public static function middleware(array|string $middlewares): self
    {
        if (is_string($middlewares)) {
            $middlewares = [$middlewares];
        }

        self::$currentMiddlewareGroup = $middlewares;
        return new self;
    }

    public function group(callable $callback): void
    {
        $callback();

        self::$currentMiddlewareGroup = []; // limpa após agrupar
    }

    public static function dispatch(string $requestUri, string $method = 'GET'): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
            $uri = trim($uri, '/');
        }

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach (self::$routes[$method] ?? [] as $route) {
            $routeUri = $route['uri'];

            $pattern = preg_replace_callback('#\{([\w]+)\}#', function ($matches) {
                return '(?P<' . $matches[1] . '>[a-zA-Z0-9_-]+)';
            }, $routeUri);

            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$controllerName, $methodName] = explode('@', $route['action']);
                $controllerClass = "App\\Http\\Controllers\\$controllerName";

                if (!class_exists($controllerClass)) {
                    http_response_code(500);
                    echo "Erro: Controller {$controllerClass} não encontrado.";
                    return;
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $methodName)) {
                    http_response_code(500);
                    echo "Erro: Método {$methodName} não existe no controller {$controllerClass}.";
                    return;
                }

                $request = new Request();

                // Aplica os middlewares globais e de rota
                $middlewares = $route['middleware'] ?? [];
                Kernel::handle($request, $middlewares);

                $reflection = new \ReflectionMethod($controller, $methodName);
                $args = [];

                foreach ($reflection->getParameters() as $param) {
                    $type = $param->getType();

                    if ($type && $type->getName() === Request::class) {
                        $args[] = $request;
                    } else {
                        $args[] = array_shift($params);
                    }
                }

                $response = $reflection->invokeArgs($controller, $args);

                if ($response instanceof \App\Foundation\Response) {
                    $response->send();
                } else {
                    echo $response;
                }

                return;
            }
        }

        http_response_code(404);
        echo "404 - Rota não encontrada: {$uri}";
    }
}
