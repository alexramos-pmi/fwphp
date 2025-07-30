<?php

namespace App\Adapters\Contracts;

interface ImageUploadAdapter
{
    public function setFile(array $file): ImageUploadAdapter;

    public function setUploadDir(string $path): ImageUploadAdapter;

    public function setDimensions(int $width, int $height): ImageUploadAdapter;

    public function keepAspectRatio(bool $keep): ImageUploadAdapter;

    public function unlink(string $cover): ImageUploadAdapter;

    public function upload(): array;
}