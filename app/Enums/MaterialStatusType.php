<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NotYet()
 * @method static static Combin()
 * @method static static Done()
 */
final class MaterialStatusType extends Enum
{
    const NotYet =   0;
    const Combin =   1;
    const Done = 2;
}
