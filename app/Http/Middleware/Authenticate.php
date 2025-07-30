<?php 

namespace App\Http\Middleware;

use Exception;
use App\Core\Auth;
use App\Http\Request;
use App\Foundation\Http;

class Authenticate
{
    public static function handle(Request $request, \Closure $next)
    {
        if(!Auth::check())
        {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

            if($isAjax)
            {
                if(!headers_sent())
                {
                    http_response_code(401);
                    header('Content-Type: application/json');
                }

                alert("Não autorizado", "Usuário não autenticado!  Clique no botão abaixo e faça login novamente.", Http::UNAUTHORIZED);

                exit;
            }

            $urlBase = env('APP_ENV') === 'local' ? env('APP_URL') . '/public' : env('APP_URL');

            if(!headers_sent())
            {
                header("Location: {$urlBase}/login");

                exit;
            }
            else
            {
                echo "<script>window.location.href = '{$urlBase}/login';</script>";
                
                exit;
            }
        }

        return $next($request);
    }
}