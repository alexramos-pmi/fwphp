<?php

session_start();

// Cabeçalhos CORS no início do index.php ou middleware
header('Access-Control-Allow-Origin: *'); // ou origem específica
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

if(!isset($_SESSION['_csrf_token']))
{
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../vendor/autoload.php';

loadEnv();

use App\Routing\Route;

// Carrega rotas do sistema
require_once __DIR__ . '/../routes/web.php';

// Processa requisição atual
Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
