<?php

namespace App\Support;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Validate
{
    private $data;
    private $errors = [];
    private $model;
    private $id;

    public function __construct($data, $id = 0)
    {
        $this->data = $data;
        $this->id = (int) $id;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function require($campo, $label)
    {
        if((empty($this->data[$campo]) && !is_numeric($this->data[$campo])) || $this->data[$campo] === 'null' || $this->data[$campo] === 'undefined' || $this->data[$campo] === null)
        {
            $this->errors[$campo] = "O campo [ {$label} ] é obrigatório";
        }
    }

    public function unique($campo, $label)
    {
        $model = $this->model->where($campo, $this->data[$campo])->first();

        if($model)
        {
            if($this->id <= 0)
            {
                $this->errors[$campo] = "O campo [ {$label} ] não pode ser duplicado";
            }
            else
            {
                if($this->id != $model->id)
                {
                    $this->errors[$campo] = "O campo [ {$label} ] não pode ser duplicado";
                }
            }
        }
    }

    public function max($campo, $label, $max)
    {
        if(strlen($this->data[$campo]) > $max)
        {
            $this->errors[$campo] = "O campo [ {$label} ] não pode ser maior que {$max}";
        }
    }

    public function date($campo, $label)
    {
        $d = DateTime::createFromFormat('d/m/Y', $this->data[$campo]);

        if(!$d || $d->format('d/m/Y') !== $this->data[$campo])
        {
            $this->errors[$campo] = "O campo [ {$label} ] não é um tipo data";
        }
    }

    public function getErrorsFull()
    {
        return $this->errors;
    }

    public function getErrors()
    {
        $text = '';
        $erros = count($this->errors) > 2 ? 'erros' : 'erro';

        $text = (count($this->errors) > 1 ? "  (mais " . (count($this->errors) - 1) . " {$erros})" : $text);

        return count($this->errors) > 0 ? current($this->errors)  . $text : '';
    }

    public function setErrorsFull(array $params)
    {
        $this->errors = $params;
    }
}
