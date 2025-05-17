<?php

namespace App\Support;

class Cabecalho
{
    private $escola;
    private $logo;
    private $municipio;
    private $secretaria;

    public function __construct($escola)
    {
        $this->escola = $escola;
    }

    public function setLogo($logo)
    {
        $img = new ElementHtml('img');
        $img->style = 'width: 60px; heigth: 60px;';
        $img->src = $logo;

        $this->logo = $img->show();

        return $this;
    }

    public function setMunicipio($municipio)
    {
        $span = new ElementHtml('span');
        $span->style = 'display: block;';
        $span->add($municipio);

        $this->municipio = $span->show();

        return $this;
    }

    public function setSecretaria($secretaria)
    {
        $span = new ElementHtml('span');
        $span->style = 'display: block;';
        $span->add($secretaria);

        $this->secretaria = $span->show();

        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function getTexto()
    {
        $container = new ElementHtml('div');
        $container->style = 'font-size: 9pt;';

        $escola = new ElementHtml('span');
        $escola->style = 'display: block;';
        $escola->add($this->escola->nome);

        $endereco = $this->escola->endereco ? $this->escola->endereco : '';
        $endereco .= $this->escola->numero ? ', ' . $this->escola->numero : '';
        $endereco .= $this->escola->bairro ? ' / ' . $this->escola->bairro()->nome . ' / ' : '';
        $endereco .= 'IPIAÚ-BA / CEP: 45570-000';

        $enderecoCompleto = new ElementHtml('span');
        $enderecoCompleto->style = 'display: block';
        $enderecoCompleto->add(mb_strtoupper($endereco));

        $container->add($this->municipio);
        $container->add($this->secretaria);
        $container->add($escola->show());
        $container->add($enderecoCompleto->show());

        return $container->show();
    }
}
