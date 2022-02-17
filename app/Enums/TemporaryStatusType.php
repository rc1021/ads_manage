<?php

namespace App\Enums;

/**
 * @method static static NotYet()
 * @method static static Combin()
 * @method static static Done()
 */
final class TemporaryStatusType extends BaseEnum
{
    const NotYet = 0;
    const Combin = 1;
}
