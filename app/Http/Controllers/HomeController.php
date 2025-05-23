<?php

namespace App\Http\Controllers;

use App\Models\Conn\DB;

class HomeController
{
    public function index()
    {
        //$usuarios = DB::table('users')->get();

        //dd($usuarios);

        return inertia('Home/Index', ['mensagem' => 'Bem-vindo!']);
    }
}