<?php

namespace App\Entity;

use ReflectionClass;

abstract class Updatabale
{
    public function update(...$args): void
    {
        $class = new ReflectionClass(get_class($this));
        $constructor = $class->getConstructor();
        $parameters = $constructor->getParameters();
        $properties = $class->getProperties();

        foreach ($parameters as $key => $parameter) {
            foreach ($properties as $property) {
                if ($property->getName() == $parameter->getName()) {
                    $property->setValue($this, $args[$key]);
                }
            }
        }
    }
}