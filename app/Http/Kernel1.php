<?php 

namespace App\Http;

use App\Http\Request;

class Kernel1
{
    protected static array $globalMiddlewares = [
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    protected static array $routeMiddlewares = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // outros middlewares nomeados...
    ];

    public static function handle(Request $request, array $routeMiddlewareKeys = []): void
    {
        // Monta lista completa de middlewares a serem executados
        $middlewareClasses = array_merge(
            self::$globalMiddlewares,
            array_map(fn($key) => self::$routeMiddlewares[$key] ?? null, $routeMiddlewareKeys)
        );

        $middlewareClasses = array_filter($middlewareClasses);

        // Função final (última da cadeia): Controller
        $core = function ($req) {
            // Aqui você pode chamar o Controller real
            // Ex: Router::dispatch($req);
            return null;
        };

        // Envelopa cada middleware ao redor do próximo
        $pipeline = array_reduce(
            array_reverse($middlewareClasses),
            function ($next, $middlewareClass) {
                return function ($req) use ($middlewareClass, $next) {
                    return $middlewareClass::handle($req, $next);
                };
            },
            $core
        );

        $pipeline($request);
    }
}