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

        $url = url('images/d9fdd23ea135913118a7234f586bbcee055e7a74.jpg');
        //$url = url('images/e7f74c30bf1faf60d0bf607c6f481585d00fe9d7.jpg');

        echo "<img src='{$url}' />";

        //return inertia('Home/Index', ['mensagem' => 'Bem-vindo!']);
    }
}