<?php

session_start();

if(!isset($_SESSION['_csrf_token']))
{
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../vendor/autoload.php';

loadEnv();

require_once __DIR__ . '/../bootstrap/app.php';

use App\Routing\Route;

//Display errors
if(env('APP_ENV') === 'production')
{
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
}
else 
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Carrega rotas do sistema
require_once __DIR__ . '/../routes/web.php';

// Processa requisição atual
Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
