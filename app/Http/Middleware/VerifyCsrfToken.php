<?php 

namespace App\Http\Middleware;

use App\Http\Request;

class VerifyCsrfToken
{
    protected static array $except = [
        '/api/ping',
        '/webhook/github',
    ];

    public static function handle(Request $request, \Closure $next)
    {
        if ($request->method() === 'GET') return $next($request);

        $uri = parse_url($request->server['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        foreach (self::$except as $exceptUri) {
            if (preg_match('#^' . preg_quote($exceptUri, '#') . '$#', $uri)) {
                return $next($request);
            }
        }

        if (!$request->validateCsrfToken()) {
            http_response_code(419);
            echo 'Token CSRF inválido ou ausente.';
            exit;
        }

        return $next($request);
    }
}