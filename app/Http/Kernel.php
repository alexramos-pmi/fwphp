<?php

namespace App\Http;

use App\Http\Request;

class Kernel
{
    protected static array $globalMiddlewares = [
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    protected static array $routeMiddlewares = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // outros middlewares nomeados...
    ];

    public static function handle(Request $request, array $routeMiddlewares = []): void
    {
        // globais
        foreach(self::$globalMiddlewares as $middlewareClass)
        {
            $middlewareClass::handle($request);
        }

        // específicos de rota
        foreach($routeMiddlewares as $middlewareKey)
        {
            $middlewareClass = self::$routeMiddlewares[$middlewareKey] ?? null;

            if($middlewareClass && method_exists($middlewareClass, 'handle'))
            {
                $middlewareClass::handle($request);
            }
        }
    }
}
