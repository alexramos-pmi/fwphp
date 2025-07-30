<?php

namespace App\Core;

use Exception;

class Container
{
    protected static array $bindings = [];

    public static function bind(string $abstract, string|callable $concrete)
    {
        self::$bindings[$abstract] = $concrete;
    }

    public static function resolve(string $class)
    {
        // Se estiver no bindings, retorna a implementação correta
        if(isset(self::$bindings[$class]))
        {
            $concrete = self::$bindings[$class];

            if(is_callable($concrete))
            {
                return $concrete(); // você pode usar closures também
            }

            $class = $concrete; // troca para a implementação concreta
        }

        $reflection = new \ReflectionClass($class);

        if(!$reflection->isInstantiable())
        {
            throw new Exception("Classe {$class} não é instanciável.");
        }

        $constructor = $reflection->getConstructor();

        if(!$constructor)
        {
            return new $class;
        }

        $dependencies = [];

        foreach($constructor->getParameters() as $param)
        {
            $type = $param->getType();

            if (!$type || $type->isBuiltin()) {
                throw new Exception("Não consigo resolver {$param->getName()} para {$class}");
            }

            $dependencies[] = self::resolve($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}