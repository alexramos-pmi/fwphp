<?php 

namespace App\Http;

use App\Http\Request;

class Kernel
{
    protected static array $globalMiddlewares = [
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\SessionTimeout::class, // Adicione aqui
        // outros middlewares globais...
    ];

    protected static array $routeMiddlewares = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // outros middlewares nomeados...
    ];

    public static function handle(Request $request, callable $controllerCallback, array $routeMiddlewareKeys = [])
    {
        // Monta lista completa de middlewares a serem executados
        $middlewareClasses = array_merge(
            
            self::$globalMiddlewares,
            array_map(fn($key) => self::$routeMiddlewares[$key] ?? null, $routeMiddlewareKeys)
        );

        $middlewareClasses = array_filter($middlewareClasses);

        // Função final (última da cadeia): o Controller real
        $core = fn($req) => $controllerCallback($req);

        // Envelopa cada middleware ao redor do próximo
        $pipeline = array_reduce(

            array_reverse($middlewareClasses),

            function($next, $middlewareClass)
            {
                return fn($req) => $middlewareClass::handle($req, $next);
            },

            $core
        );

        // Executa toda a cadeia e retorna a resposta final
        return $pipeline($request);
    }
}