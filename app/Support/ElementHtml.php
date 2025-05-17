<?php

namespace App\Support;

class ElementHtml
{
    private $name;  // nome da TAG
    private $properties;    // propriedades da TAG
    protected $children;

    /**
     * método construtor
     * instancia uma tag html
     * @param $name = nome da tag
     */
    public function __construct($name)
    {
        // define o nome do elemento
        $this->name = $name;
    }

    /**
     * método __set()
     * intercepta as atribuições à propriedades do objeto
     * @param $name = nome da propriedade
     * @param $value = valor
     */
    public function __set($name, $value)
    {
        // armazena os valores atribuídos
        // ao array properties
        $this->properties[$name] = $value;
    }

    /**
     * método add()
     * adiciona uma elemento filho
     * @param $child = objeto filho
     */
    public function add($child)
    {
        $this->children[] = $child;
    }

    /**
     * método show()
     * exibe a teg na tela, juntamente com seu conteúdo
     */
    public function show()
    {
        $tag = '';

        // abre a tag
        $tag .= $this->open();

        // se possue conteúdo
        if($this->children):

            // percorre todos objetos filhos
            foreach($this->children as $child):

                // se for objeto
                if(is_object($child)):

                    $tag .= $child->show();

                elseif((is_string($child)) or (is_numeric($child))):

                    // se for texto
                    $tag .= $child;

                endif;

            endforeach;

            // fecha a tag
            $tag .= $this->close();

        endif;

        //return json_encode($tag);
        return $tag;
    }

    /**
     * método open()
     * exibe a tag de abertura na tela
     */
    private function open()
    {
        $result = '';
        // exibe a tag de abertura

        $result .= "<{$this->name}";

        if($this->properties):

            //percorre as propriedades
            foreach($this->properties as $name => $value):

                $name = str_replace('_', '-', $name);

                $result .= " {$name}=\"{$value}\"";

            endforeach;

        endif;

        $result .= '>';

        return $result;
    }

    /**
     * método close()
     * Fecha uma tag HTML
     */
    private function close()
    {
        return "</{$this->name}>";
    }
}
