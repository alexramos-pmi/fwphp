<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Request;
use App\Foundation\Http;

class SessionTimeout
{
    public static function handle(Request $request, Closure $next)
    {
        $lifetime = env('SESSION_LIFETIME', 120); // Tempo em minutos

        if(!isset($_SESSION['last_activity']))
        {
            $_SESSION['last_activity'] = time();
        }
        elseif(time() - $_SESSION['last_activity'] > $lifetime * 60)
        {
            // Se o tempo de inatividade exceder o limite, destrua a sessão
            session_unset();
            session_destroy();
            
            alert("Sessão encerrada", "Sua sessão expirou. Clique no botão abaixo e faça o login novamente.", Http::UNAUTHORIZED);

            exit;
        }

        return $next($request);
    }
}