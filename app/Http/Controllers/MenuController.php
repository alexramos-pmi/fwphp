<?php 

namespace App\Http\Controllers;

use App\Foundation\Http;

class MenuController
{
    public function index()
    {
        $menu = [
            [
                'title' => 'Home',
                'icon' => 'mdi-home-outline',
                'children' => [
                    ['title' => 'Dashboard', 'icon' => 'mdi-circle-medium', 'route' => ''],
                ],
            ],
            [
                'title' => 'Acesso',
                'icon' => 'mdi-lock-outline',
                'children' => [
                    ['title' => 'Usuário', 'icon' => 'mdi-circle-medium', 'route' => 'usuarios'],
                ],
            ],
            // [
            //     'title' => 'Sair',
            //     'icon' => 'mdi-exit-to-app',
            //     'route' => 'logout'
            // ],
        ];

        return response()->json(['menu' => $menu], Http::OK);
    }
}