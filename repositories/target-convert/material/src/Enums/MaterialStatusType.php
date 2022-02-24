<?php

namespace TargetConvert\Material\Enums;

/**
 * @method static static NotYet()
 * @method static static ProcessMaterialTemporary()
 * @method static static Done()
 */
final class MaterialStatusType extends BaseEnum
{
    const NotYet = 1 << 0;
    const ProcessMaterialTemporary = 1 << 1;
    const Done = 1 << 2;
}
