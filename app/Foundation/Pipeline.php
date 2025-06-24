<?php

namespace App\Foundation;

use Closure;

class Pipeline
{
    protected mixed $passable;
    protected array $pipes = [];

    public function send(mixed $passable): static
    {
        $this->passable = $passable;

        return $this;
    }

    public function through(array $pipes): static
    {
        $this->pipes = $pipes;

        return $this;
    }

    public function then(Closure $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $destination
        );

        return $pipeline($this->passable);
    }

    protected function carry(): Closure
    {
        return function(Closure $stack, $pipe)
        {
            return function($passable) use ($stack, $pipe)
            {
                if (is_string($pipe)) {
                    $pipe = new $pipe;
                }

                return $pipe->handle($passable, $stack);
            };
        };
    }
}