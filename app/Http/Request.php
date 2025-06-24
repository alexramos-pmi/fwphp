<?php

namespace App\Http;

class Request
{
    protected array $get;
    protected array $post;
    protected array $server;
    protected array $custom = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;

        $this->mergeNonPostInput();
    }

    public function server(string $key = '', $default = null)
    {
        if($key === null)
        {
            return $this->server;
        }

        return $this->server[$key] ?? $default;
    }

    protected function mergeNonPostInput(): void
    {
        $method = $this->method();

        // Apenas métodos que não preenchem $_POST automaticamente
        if (!in_array($method, ['GET', 'POST'])) {
            $input = file_get_contents('php://input');

            $contentType = $this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '';

            // Se for JSON (usado geralmente em APIs)
            if (str_contains($contentType, 'application/json')) {
                $decoded = json_decode($input, true);
                if (is_array($decoded)) {
                    $this->custom = array_merge($this->custom, $decoded);
                }
            }

            // Se for application/x-www-form-urlencoded (muito comum com _method spoofing)
            elseif (str_contains($contentType, 'application/x-www-form-urlencoded')) {
                parse_str($input, $parsed);
                if (is_array($parsed)) {
                    $this->custom = array_merge($this->custom, $parsed);
                }
            }
        }
    }

    public function input(string $key, $default = null): mixed
    {
        return $this->custom[$key] ?? $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->custom);
    }

    public function set(string $key, mixed $value): void
    {
        $this->custom[$key] = $value;
    }

    public function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->custom[$key] = $value;
        }
    }

    public function remove(string $key): void
    {
        unset($this->custom[$key], $this->post[$key], $this->get[$key]);
    }

    public function removeMany(array $keys): void
    {
        foreach ($keys as $key) {
            $this->remove($key);
        }
    }

    public function method(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'POST' && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }

        return strtoupper($method);
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function validateCsrfToken(): bool
    {
        $tokenSession = $_SESSION['_csrf_token'] ?? '';

        // 1. Token vindo por POST
        if (isset($this->post['_token']) && hash_equals($tokenSession, $this->post['_token'])) {
            return true;
        }
    
        // 2. Token vindo por cabeçalho (header)
        $tokenHeader = null;
    
        // Tenta com getallheaders()
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            $tokenHeader = $headers['X-CSRF-TOKEN'] ?? $headers['x-csrf-token'] ?? null;
        }
    
        // Fallback manual para ambientes que não suportam getallheaders()
        if (!$tokenHeader && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $tokenHeader = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
    
        // Valida
        if ($tokenHeader && hash_equals($tokenSession, $tokenHeader)) {
            return true;
        }
    
        return false;
    }

    public function file(string $key)
    {
        return $_FILES[$key] ?? null;
    }
}