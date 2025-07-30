<?php

use App\Core\Auth;
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
        // Modo desenvolvimento? Verifica se o Vite está rodando em localhost:5173

        if(env('APP_ENV') !== "production")
        {
            // Modo desenvolvimento (Vite Dev Server ativo)
            $url = "http://localhost:5173/{$entry}";

            return <<<HTML
                <script type="module" src="{$url}"></script>
            HTML;
        }

        // Modo produção: lê o manifest
        $manifestPath = __DIR__ . '/../public/build/.vite/manifest.json';

        if(!file_exists($manifestPath))
        {
            throw new Exception("Vite manifest não encontrado: {$manifestPath}");
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if(!isset($manifest[$entry]))
        {
            throw new Exception("Entrada '{$entry}' não encontrada no manifest.");
        }

        $tags = '';

        // CSS
        if(isset($manifest[$entry]['css']))
        {
            foreach($manifest[$entry]['css'] as $cssFile)
            {
                $tags .= '<link rel="stylesheet" href="/build/' . $cssFile . '">' . PHP_EOL;
            }
        }

        // JS
        $jsFile = $manifest[$entry]['file'];
        $tags .= '<script type="module" src="/build/' . $jsFile . '"></script>' . PHP_EOL;

        return $tags;
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
        // if($env === 'production')
        // {
        //     $envFile = '.env.production';
        // }

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

if(!function_exists('logger'))
{
    function logger(string $message, string $level = 'info'): void
    {
        $file = __DIR__ . '/../storage/logs/' . date('Y-m-d') . '.log';

        $entry = sprintf(
            "[%s] [%s] [%s] [%s] %s\n",
            date('Y-m-d H:i:s'),
            Auth::user()->name,
            Auth::user()->email,
            mb_strtoupper($level),
            $message
        );

        file_put_contents($file, $entry, FILE_APPEND);
    }
}

if(!function_exists('linkSimbolico'))
{
    function linkSimbolico(): void
    {
        $target = __DIR__ . '/../storage/images'; // Caminho para a pasta de uploads
        $link = __DIR__ . '/../public/images'; // Caminho para o link simbólico na pasta public

        if(!file_exists($link))
        {
            symlink($target, $link);
        }
    }
}

if(!function_exists('url'))
{
    function url(string $path): string
    {
        // Obtém o esquema (http ou https)
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        // Obtém o nome do host (por exemplo, 'seudominio.com')
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // Obtém o caminho do script (por exemplo, '/index.php')
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

        // Remove o nome do script para obter o caminho base
        $basePath = rtrim(str_replace(basename($scriptName), '', $scriptName), '/');

        // Constrói a URL completa
        return "{$scheme}://{$host}{$basePath}/{$path}";
    }
}

if(!function_exists('public_path'))
{
    /**
     * Retorna o caminho absoluto para a pasta public do projeto,
     * relativo ao diretório onde este helper está localizado.
     *
     * @param string $path Caminho relativo dentro da pasta public.
     * @return string Caminho absoluto no sistema de arquivos.
     */
    function public_path(string $path = ''): string
    {
        // Ajuste o caminho conforme a estrutura do seu projeto
        $base = __DIR__ . '/../public';

        $path = $path ? $base . '/' . ltrim($path, '/') : $base;

        return $path;
    }
}

if(!function_exists('cachear'))
{
    function cachear($chave, callable $callback, $expiracao = 86400)
    {
        static $memCache = [];

        if (isset($memCache[$chave])) {
            return $memCache[$chave];
        }

        $arquivo = __DIR__ . "/../cache/{$chave}.php";

        if (file_exists($arquivo)) {
            $tempo = filemtime($arquivo);
            if ((time() - $tempo) < $expiracao) {
                return $memCache[$chave] = require $arquivo;
            }
        }

        $dados = $callback();

        // 🔁 Garante que $dados seja array
        if ($dados instanceof \Illuminate\Support\Collection) {
            $dados = $dados->all();
        }

        if (!is_array($dados)) {
            $dados = [$dados];
        }

        // ✅ Converte Eloquent → array → objeto (stdClass)
        $dadosObjetos = array_map(function ($item) {
            $array = method_exists($item, 'toArray') ? $item->toArray() : (array) $item;
            return (object) $array;
        }, $dados);

        // 💾 Salva como array de objetos em arquivo .php
        $conteudo = '<?php return ' . var_export($dadosObjetos, true) . ';';
        file_put_contents($arquivo, $conteudo);

        return $memCache[$chave] = $dadosObjetos;
    }
}

if(!function_exists('alert'))
{
    function alert(string $title, string $message, int $code): void
    {
        $url = env('APP_URL') . '/public/login';
        
        echo <<<HTML
            <!DOCTYPE html>
            <html lang="pt-BR">
                <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1" />
                    <title>404 - Página não encontrada</title>
                    <style>
                        body {
                            background: #f8f9fa;
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            color: #333;
                        }
                        .container {
                            text-align: center;
                            background: white;
                            padding: 40px 60px;
                            border-radius: 10px;
                            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                            max-width: 400px;
                        }
                        h1 {
                            font-size: 72px;
                            margin: 0;
                            color: #e63946;
                        }
                        h2 {
                            margin-top: 10px;
                            font-weight: 400;
                            color: #555;
                        }
                        p {
                            margin: 20px 0;
                            font-size: 18px;
                            color: #666;
                        }
                        a {
                            text-decoration: none;
                            background: #457b9d;
                            color: white;
                            padding: 12px 25px;
                            border-radius: 6px;
                            font-weight: 600;
                            transition: background-color 0.3s ease;
                            display: inline-block;
                        }
                        a:hover {
                            background: #1d3557;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>{$code}</h1>
                        <h2>{$title}</h2>
                        <p>{$message}</p>
                        <a href="{$url}">Página de Login</a>
                    </div>
                </body>
            </html>
        HTML;
    }
}

