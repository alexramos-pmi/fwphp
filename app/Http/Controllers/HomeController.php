<?php

namespace App\Http\Controllers;

use App\Models\Conn\DB;

class HomeController
{
    public function index()
    {
        // $estados = DB::table('tb_ufs')->get();
        // $municipios = DB::table('tb_municipios')->where('sg_uf', 'BA')->orderBy('nome')->get();

        // dd($municipios);

        return inertia('Home/Index', ['mensagem' => 'Bem-vindo!']);
    }
}