<?php

namespace App\Http\Controllers;

use App\Models\Conn\DB;
use App\Core\EmailService;
use Exception;

class HomeController
{
    public function index()
    {
        //linkSimbolico();
        //$url = url('images/d9fdd23ea135913118a7234f586bbcee055e7a74.jpg');
        //$url = url('images/e7f74c30bf1faf60d0bf607c6f481585d00fe9d7.jpg');

        //echo "<img src='{$url}' />";

        // $emailService = new EmailService();

        // $destinatario = 'alexramos.pmi@gmail.com';
        // $assunto = 'Bem-vindo à Nossa Plataforma';
        // $mensagem = '<p>Olá,</p><p>Estamos felizes em tê-lo conosco. Aproveite todos os recursos disponíveis.</p>';

        // try
        // {
        //     $emailService->enviar($destinatario, $assunto, $mensagem);

        //     echo 'E-mail enviado com sucesso!';
        // }
        // catch(Exception $e)
        // {
        //     echo 'Erro ao enviar e-mail: ' . $emailService->getErrors();
        // }

        return inertia('Home/Index', ['mensagem' => 'Bem-vindo!']);
    }
}