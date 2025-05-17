<?php

use Symfony\Component\VarDumper\VarDumper;

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
    function loadEnv(string $path = __DIR__ . '/../.env'): void
    {
        if(isset($_SERVER['_ENV_LOADED']) || !file_exists($path))
        {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach($lines as $line)
        {
            $line = trim($line);

            if($line === '' || str_starts_with($line, '#'))
            {
                continue;
            }

            // Ignora linhas sem "="
            if(!str_contains($line, '='))
            {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove aspas se necessário
            if(
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            )
            {
                $value = substr($value, 1, -1);
            }

            // Conversões de tipo
            $lower = strtolower($value);

            if($lower === 'true')
            {
                $value = true;
            }
            elseif ($lower === 'false')
            {
                $value = false;
            }
            elseif ($lower === 'null')
            {
                $value = null;
            }
            elseif (is_numeric($value))
            {
                $value = $value + 0; // converte para int ou float
            }

            // Define nas superglobais e ambiente
            $_ENV[$key] = $value;
            putenv("$key=" . (is_bool($value) ? ($value ? 'true' : 'false') : $value));
        }

        $_SERVER['_ENV_LOADED'] = true;
    }
}

if(!function_exists('env'))
{
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}

