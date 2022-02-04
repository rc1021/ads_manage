<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Text()
 * @method static static Image()
 * @method static static Audio()
 * @method static static Video()
 */
final class MaterialType extends Enum
{
    const Text  = 1 << 0;
    const Image = 1 << 1;
    const Audio = 1 << 2;
    const Video = 1 << 3;
}
