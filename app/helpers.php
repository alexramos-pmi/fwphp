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

function vite(string $entry)
{
    $isDev = ($_ENV['APP_ENV'] ?? 'local') === 'local';

    if($isDev)
    {
        // Usa o servidor de desenvolvimento Vite
            return <<<HTML
                <script type="module" src="http://localhost:5173/resources/@vite/client"></script>
                <script type="module" src="http://localhost:5173/{$entry}"></script>
            HTML;
    }

    // Produção: usa arquivos compilados
    $manifestPath = __DIR__ . '/../public/dist/manifest.json';

    if(!file_exists($manifestPath))
    {
        throw new Exception("Vite manifest file not found at {$manifestPath}");
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    if(!isset($manifest[$entry]))
    {
        throw new Exception("Entry {$entry} not found in Vite manifest");
    }

    $path = '/dist/' . $manifest[$entry]['file'];

    $imports = '';

    if(!empty($manifest[$entry]['css']))
    {
        foreach($manifest[$entry]['css'] as $css)
        {
            $imports .= "<link rel=\"stylesheet\" href=\"/dist/{$css}\">\n";
        }
    }

    return <<<HTML
        {$imports}<script type="module" src="{$path}"></script>
    HTML;
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

if(!function_exists('view'))
{
    function view(string $component, array $props = [], string $title = 'Mini Framework')
    {
        // Disponibiliza os dados para o layout
        $page = $component;
        $jsonProps = json_encode($props); // se quiser passar props futuramente

        require __DIR__ . '/../resources/views/layout.php';
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

