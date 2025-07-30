<?php

namespace App\Http\Controllers;

use App\Adapters\Contracts\EmailAdapter;
use Exception;

class HomeController
{
    // public function __construct(private EmailAdapter $emailAdapter){}

    public function index()
    {
        // $destinatario = 'alexramos.pmi@gmail.com';
        // $assunto = 'Bem-vindo à Nossa Plataforma';
        // $mensagem = '<p>Olá,</p><p>Estamos felizes em tê-lo conosco. Aproveite todos os recursos disponíveis.</p>';

        // try
        // {
        //     $this->emailAdapter->enviar($destinatario, $assunto, $mensagem);
        // }
        // catch(Exception $e)
        // {
        //     dd('Erro ao enviar e-mail: ' . $this->emailAdapter->getErrors());
        // }

        return inertia('Home/Index', ['mensagem' => 'Bem-vindo!']);
    }
}