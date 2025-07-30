<?php

use App\Core\Container;
use App\Adapters\Contracts\PdfAdapter;
use App\Adapters\Contracts\EmailAdapter;
use App\Adapters\Contracts\ValidateAdapter;
use App\Adapters\Contracts\ImageUploadAdapter;

//Injeta dependências nos construtores dos controladores das rotas: param 1 = interface, param 2 = classe concreta

Container::bind(PdfAdapter::class, 'App\Adapters\Pdf\DomPdfAdapter');
Container::bind(ImageUploadAdapter::class, 'App\Adapters\Image\InterventionAdapter');
Container::bind(EmailAdapter::class, 'App\Adapters\Email\PHPMailerAdapter');

//Injeta dependências de validação de formulários nos controladores de criação (create) e atualização (update)
//Obs.: Captura a URI e amarzena em $uri, depois verifica se existe determinada palavra, ex.: 'usuarios', em $uri

$uri = $_SERVER['REQUEST_URI'];

switch(true)
{
    case str_contains($uri, 'usuarios/store') || str_contains($uri, 'usuarios/update'):
        Container::bind(ValidateAdapter::class, 'App\Adapters\Validations\UsuarioValidationAdapter');
        break;
    case str_contains($uri, 'estoques/store') || str_contains($uri, 'estoques/update'):
        Container::bind(ValidateAdapter::class, 'App\Adapters\Validations\EstoqueValidationAdapter');
        break;
    case str_contains($uri, 'consumos/store') || str_contains($uri, 'consumos/update'):
        Container::bind(ValidateAdapter::class, 'App\Adapters\Validations\ConsumoValidationAdapter');
        break;
    default:
        Container::bind(ValidateAdapter::class, 'App\Adapters\Validations\DefaultValidationAdapter');
        break;
}
