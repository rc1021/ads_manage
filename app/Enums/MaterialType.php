<?php

namespace App\Enums;

/**
 * @method static static Text()
 * @method static static Image()
 * @method static static Audio()
 * @method static static Video()
 */
final class MaterialType extends BaseEnum
{
    const Text  = 1 << 0;
    const Image = 1 << 1;
    const Audio = 1 << 2;
    const Video = 1 << 3;
}
