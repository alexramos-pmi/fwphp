<?php 

namespace App\Foundation;

class Response
{
    protected mixed $content = '';
    protected int $status = Http::OK;
    protected array $headers = [];
    protected bool $sent = false;

    public function make(mixed $content = '', int $status = Http::OK, array $headers = []): static
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;

        return $this;
    }

    public function json(array $data, int $status = Http::OK, array $headers = []): static
    {
        $this->content = json_encode($data);
        $this->status = $status;
        $this->headers = array_merge(['Content-Type' => 'application/json'], $headers);

        return $this;
    }

    public static function redirect(string $url, int $status = 302): never
    {
        header("Location: $url", true, $status);

        exit;
    }

    public function send(): void
    {
        if ($this->sent) return;

        http_response_code($this->status);

        foreach($this->headers as $key => $value)
        {
            header("$key: $value");
        }

        echo $this->content;
        $this->sent = true;
    }

    public function __destruct()
    {
        $this->send();
    }
}