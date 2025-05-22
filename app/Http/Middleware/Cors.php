<?php

namespace App\Http\Middleware;

use App\Http\Request;

class Cors
{
    public static function handle(Request $request): void
    {
        $origin = $request->server('HTTP_ORIGIN', '');
        $allowedOrigins = array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', '*')));
        $isAllowed = false;

        foreach ($allowedOrigins as $allowed)
        {
            if($allowed === '*')
            {
                header('Access-Control-Allow-Origin: *');
                $isAllowed = true;

                break;
            }

            // Wildcard: *.dominio.com
            if(str_starts_with($allowed, '*'))
            {
                $pattern = '#' . str_replace('\*', '([a-z0-9-]+\.)?', preg_quote($allowed, '#')) . '$#i';

                if(preg_match($pattern, $origin))
                {
                    header("Access-Control-Allow-Origin: $origin");
                    $isAllowed = true;

                    break;
                }
            }

            if($origin === $allowed)
            {
                header("Access-Control-Allow-Origin: $origin");
                $isAllowed = true;
                
                break;
            }
        }

        // Se origin está presente mas não é permitido
        if($origin && !$isAllowed)
        {
            logger("CORS bloqueado para origem: {$origin}", 'warning');
            http_response_code(403);
            echo 'Origem não permitida.';

            exit;
        }

        // Só envia Allow-Credentials se for origem específica
        if($origin && $origin !== '*')
        {
            header('Access-Control-Allow-Credentials: true');
        }

        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        // Responde requisição OPTIONS (preflight)
        if($request->method() === 'OPTIONS')
        {
            http_response_code(204);
            
            exit;
        }
    }
}