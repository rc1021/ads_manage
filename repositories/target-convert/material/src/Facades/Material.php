<?php

namespace TargetConvert\Material\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Material.
 *
 * @method static void routes()
 *
 * @see \TargetConvert\Material\Material
 */
class Material extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TargetConvert\Material\Material::class;
    }
}
