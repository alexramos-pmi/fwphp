<?php 

namespace App\Http\Middleware;

use App\Core\Auth;
use App\Foundation\Http;
use App\Http\Request;
use App\Foundation\Response;

class Authenticate
{
    public static function handle(Request $request)
    {
        if(!Auth::check())
        {
            // Requisição é AJAX (fetch/axios)?
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
            
            if($isAjax)
            {
                // Evita problemas com headers já enviados
                if(!headers_sent())
                {
                    http_response_code(401);
                    header('Content-Type: application/json');
                }

                return response()->json(["status"=> "Não autenticado!"], Http::UNAUTHORIZED);
            }
            else 
            {
                // Redirecionamento para o login em acesso direto/refresh
                if(!headers_sent())
                {
                    header('Location: http://localhost/myframework/public/login');
                    
                    exit;
                } 
                else
                {
                    echo "<script>window.location.href = 'http://localhost/myframework/public/login';</script>";

                    exit;
                }
            }

            exit;
        }
    }
}