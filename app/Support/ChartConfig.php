<?php

namespace App\Support;

class ChartConfig
{
    private \stdClass $config;
    private \stdClass $dataChart;
    private \stdClass $dataSets;
    private \stdClass $options;
    private $title;
    private $data;
    private $backgroundColor = '#bedaff';
    private $borderWidth = 0;
    private $labels;
    private $element;
    private $type = 'bar';
    private $indexAxis = 'x';

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    //@param $backgroundColor, string or array
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }

    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setIndexAxis($indexAxis)
    {
        $this->indexAxis = $indexAxis;

        return $this;
    }

    public function response()
    {
        $this->dataSets = new \stdClass();
        $this->dataChart = new \stdClass();
        $this->options = new \stdClass();
        $this->config = new \stdClass();

        $this->dataSets->label = $this->title;
        $this->dataSets->data = $this->data;
        $this->dataSets->backgroundColor = $this->backgroundColor;
        $this->dataSets->borderWidth = $this->borderWidth;

        $this->dataChart->labels = $this->labels;
        $this->dataChart->datasets = [$this->dataSets];

        $this->options->indexAxis = $this->indexAxis;

        $this->config->element = $this->element;
        $this->config->type = $this->type;
        $this->config->data = $this->dataChart;
        $this->config->options = $this->options;

        return $this->config;
    }
}
