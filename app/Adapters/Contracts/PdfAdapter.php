<?php

namespace App\Adapters\Contracts;

interface PdfAdapter
{
    public function generate(string $filename, string $content, string $orientation): void;
}
