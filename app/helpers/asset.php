<?php

function asset(string $path): string
{
    return $_ENV['APP_URL'] . '/' . ltrim($path, '/');
}