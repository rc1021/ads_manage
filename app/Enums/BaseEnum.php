<?php

namespace App\Enums;

use ReflectionClass;

class BaseEnum
{
    public static function getConstants()
    {
        $refl = new ReflectionClass(static::class);
        return $refl->getConstants();
    }

    public static function getValues()
    {
        return array_values(static::getConstants());
    }

    public static function getKeys()
    {
        return array_keys(static::getConstants());
    }

    public static function getKey($value)
    {
        return array_search($value, static::getConstants());
    }
}
