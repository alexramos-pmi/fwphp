<?php

namespace App\Adapters\Contracts;

interface EmailAdapter
{
    public function enviar(string $destinatario, string $assunto, string $mensagem, array $anexos = []): bool;

    public function getErrors(): string;
}
