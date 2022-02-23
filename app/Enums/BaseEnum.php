<?php

namespace App\Enums;

use ReflectionClass;

class BaseEnum
{
    /**
     * 取得 enum key -> value 陣列
     *
     * @return array
     */
    public static function getConstants() : array
    {
        $refl = new ReflectionClass(static::class);
        return $refl->getConstants();
    }

    /**
     * 取得所有 enum value 並回傳陣列
     *
     * @return array
     */
    public static function getValues() : array
    {
        return array_values(static::getConstants());
    }

    /**
     * 取得所有 enum 字串並回傳陣列
     *
     * @return array
     */
    public static function getKeys() : array
    {
        return array_keys(static::getConstants());
    }

    /**
     * 透過 enum value 取得 enum key 字串
     *
     * @param  mixed $value
     * @return string
     */
    public static function getKey($value) : string
    {
        return array_search($value, static::getConstants());
    }

    /**
     * 透過 字串 取得 enum value
     *
     * @param  mixed $key 不分大小寫
     * @return mixed
     */
    public static function getValue($key)
    {
        return data_get(static::getConstants(), ucfirst(strtolower($key)));
    }
}
