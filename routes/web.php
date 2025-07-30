<?php

use App\Routing\Route;

//AUTH
Route::get('login', 'AuthController@showLoginForm');
Route::post('login', 'AuthController@login');
Route::get('login/check', 'AuthController@checklogin');

Route::middleware(['auth'])->group(function()
{
    //MENU
    Route::get('menus', 'MenuController@index');
    //HOME
    Route::get('/', 'HomeController@index');
    //USUÁRIO
    Route::get('usuarios', 'UsuarioController@index');
    Route::get('usuarios/els/{er}', 'UsuarioController@els');
    Route::get('usuarios/etas/{el}', 'UsuarioController@etas');
    Route::post('usuarios/store', 'UsuarioController@store');
    Route::put('usuarios/update/{id}', 'UsuarioController@update');
    Route::delete('usuarios/delete/{id}', 'UsuarioController@destroy');
    //AUTH
    Route::post('logout', 'AuthController@logout');
});
