<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\HttpFoundation\File\File;

class MaterialImageRule implements Rule
{
    protected $message_str;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message_str = 'The :attribute verification failed.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!($value instanceof \Symfony\Component\HttpFoundation\File\File))
            return false;

        // 檢查比例
        // [1:1],
        // [1.91:1] equal [300:157],
        list($width, $height, $type, $attr) = getimagesize($value->getRealPath());
        $dar = $this->getAspectRatio($width, $height);

        if(!in_array($dar, ['1:1', '300:157'])) {
            $this->message_str = '圖片尺寸比率不正確，目前僅接受 [1:1]、[1.91:1] 的圖片。';
            return false;
        }

        // 檢查尺寸
        // 最大 1200 x 1200, 1200 x 628 -- 暫時不需要(大於這尺寸就系統調整即可)
        // 最小 600 x 600, 600 x 314
        if($dar == '1:1' && ($width < 600 || $height < 600)) {
            $this->message_str = '圖片尺寸不正確，目前僅接受 [1:1] 寛度和高度必須 > 600; [1.91:1] 寛度必須 > 600, 高度 > 314 的圖片。';
            return false;
        }
        if($dar == '300:157' && ($width < 600 || $height < 314)) {
            $this->message_str = '圖片尺寸不正確，目前僅接受 [1:1] 寛度和高度必須 > 600; [1.91:1] 寛度必須 > 600, 高度 > 314 的圖片。';
            return false;
        }

        return true;
    }

    function getAspectRatio(int $width, int $height)
    {
        // search for greatest common divisor
        $greatestCommonDivisor = static function($width, $height) use (&$greatestCommonDivisor) {
            return ($width % $height) ? $greatestCommonDivisor($height, $width % $height) : $height;
        };

        $divisor = $greatestCommonDivisor($width, $height);

        return $width / $divisor . ':' . $height / $divisor;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message_str;
    }
}
