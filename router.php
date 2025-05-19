<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$publicPath = __DIR__ . '/public';
$filePath = $publicPath . $uri;

// Se o arquivo requisitado existir em /public, o servidor PHP deve servir normalmente
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false;
}

// Redireciona todas as requisições para index.php
require_once $publicPath . '/index.php';