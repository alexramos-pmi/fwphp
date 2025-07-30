<?php

//Inclue o autoload
require_once __DIR__ . '/../vendor/autoload.php';

//Inclue a configuração/helper do arquivo .env
loadEnv();

//Configura o tempo de sessão
$config = require __DIR__ . '/../config/session.php';

$lifetime = $config['lifetime'] * 60;
ini_set('session.gc_maxlifetime', $lifetime);
ini_set('session.cookie_lifetime', $lifetime);

//Inicia a sessão
session_start();

if(!isset($_SESSION['_csrf_token']))
{
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
}

//Inclue os dados de conexão para o Eloquent ORM
require_once __DIR__ . '/../bootstrap/app.php';

//Inclue o timezone
$appConfig = require __DIR__ . '/../config/app.php';

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

//Inclue as rotas
use App\Routing\Route;

// Carrega rotas do sistema
require_once __DIR__ . '/../routes/web.php';

//Injeta dependências nos construtores dos controladores das rotas: param 1 = interface, param 2 = classe concreta
require_once __DIR__ . '/../bootstrap/container.php';

// Processa requisição atual
Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
