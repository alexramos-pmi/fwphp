<?php

namespace App\Support;

class TableHtml
{
    private $thead;
    private $tbody;
    private $footer;
    private $lines;
    private $columns;

    public function setColumn($text, array $css = [], $props = [], $tag = 'td')
    {
        $column = new ElementHtml($tag);
        $style = '';

        if(count($css) > 0)
        {
            foreach($css as $slc => $val):

                (!isset($style) ? $style = $slc . ':' . $val . ';' : $style .= $slc . ':' . $val . ';');

            endforeach;
        }

        if(!is_null($props)):

            foreach($props as $prop => $value):

                $column->$prop = $value;

            endforeach;

        endif;

        if(!empty($style))
        {
            $column->style = $style;
        }

        $column->add($text);

        $this->columns[] = $column->show();
    }

    public function setLine()
    {
        $line = new ElementHtml('tr');

        foreach($this->columns as $column):

            $line->add($column);

        endforeach;

        $this->lines[] = $line->show();

        $this->columns = null;
    }

    public function setHeader()
    {
        $header = new ElementHtml('thead');

        foreach($this->lines as $line):

            $header->add($line);

        endforeach;

        $this->thead[] = $header->show();

        $this->lines = null;
    }

    public function setBody()
    {
        $body = new ElementHtml('tbody');

        foreach($this->lines as $line):

            $body->add($line);

        endforeach;

        $this->tbody[] = $body->show();

        $this->lines = null;
    }

    public function setFooter()
    {
        $footer = new ElementHtml('tfoot');

        foreach($this->lines as $line):

            $footer->add($line);

        endforeach;

        $this->footer[] = $footer->show();

        $this->lines = null;
    }

    public function add($css = false)
    {
        $table = new ElementHtml('table');

        if($css):

            $table->style = $css;

        endif;

        if($this->thead):

            foreach($this->thead as $head):

                $table->add($head);

            endforeach;

            $this->thead = null;

        endif;

        if($this->tbody):

            foreach($this->tbody as $body):

                $table->add($body);

            endforeach;

            $this->tbody = null;

        endif;

        if($this->footer):

            foreach($this->footer as $footer):

                $table->add($footer);

            endforeach;

            $this->footer = null;

        endif;

        return $table->show();
    }
}
