<?php 

namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
        return inertia('Home', ['mensagem' => 'Bem-vindo!']);
    }
}