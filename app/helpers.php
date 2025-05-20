<?php

use Symfony\Component\VarDumper\VarDumper;
use Dotenv\Dotenv;

if(!function_exists('csrf'))
{
    function csrf(bool $raw = false): string
    {
        if(!isset($_SESSION['_csrf_token']))
        {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        $token = $_SESSION['_csrf_token'];

        if($raw)
        {
            return $token;
        }

        return '<input type="hidden" name="_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

if(!function_exists('method'))
{
    function method(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . htmlspecialchars(strtoupper($method), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if(!function_exists('dd'))
{
    function dd(...$vars)
    {
        foreach($vars as $var)
        {
            VarDumper::dump($var);
        }
        exit;
    }
}

if(!function_exists('inertia'))
{
    function inertia($component, $props = [])
    {
        $page = [
            'component' => $component,
            'props' => $props,
            'url' => $_SERVER['REQUEST_URI'],
            'version' => '',
        ];

        if($_SERVER['HTTP_X_INERTIA'] ?? false)
        {
            header('Vary: Accept');
            header('X-Inertia: true');
            header('Content-Type: application/json');
            echo json_encode(['component' => $component, 'props' => $props, 'url' => $page['url'], 'version' => '']);
        }
        else
        {
            include __DIR__ . '../../resources/views/app.php';
        }
    }
}

if(!function_exists('vite'))
{
    function vite($entry)
    {
        //Em modo de desenvolvimento
        if(env('APP_ENV') !== 'production')
        {
            return '<script type="module" src="http://localhost:5173/' . $entry . '"></script>';
        }

        //Em modo de produção
        $manifestPath = __DIR__ . '../../public/build/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            return '<!-- manifest.json não encontrado -->';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!isset($manifest[$entry])) {
            return "<!-- Entrada {$entry} não encontrada no manifest -->";
        }

        $file = $manifest[$entry]['file'];

        return "<script type=\"module\" src=\"/build/{$file}\"></script>";
    }
}

if(!function_exists('response'))
{
    function response(): \App\Foundation\Response
    {
        return new \App\Foundation\Response();
    }
}

if(!function_exists('bcrypt'))
{
    function bcrypt(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}

if(!function_exists('loadEnv'))
{
    function loadEnv(): void
    {
        $dotenvPath = dirname(__DIR__); // ou ajuste para o caminho da raiz do projeto
        $envFile = '.env';

        // Se existir variável de ambiente APP_ENV no servidor, use-a
        $env = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null;

        if(!$env && file_exists("$dotenvPath/.env"))
        {
            // Carrega o arquivo temporariamente para descobrir o ambiente
            $lines = file("$dotenvPath/.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach($lines as $line)
            {
                if(str_starts_with($line, 'APP_ENV='))
                {
                    $env = trim(explode('=', $line, 2)[1]);
                    break;
                }
            }
        }

        // Se for produção, use .env.production
        if($env === 'production')
        {
            $envFile = '.env.production';
        }

        if(file_exists("$dotenvPath/$envFile"))
        {
            $dotenv = Dotenv::createImmutable($dotenvPath, $envFile);
            $dotenv->load();
        }
    }
}

if(!function_exists('env'))
{
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}

