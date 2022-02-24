<?php

namespace TargetConvert\Material\Rules;

use getID3;
use Illuminate\Contracts\Validation\Rule;

class MaterialVideoRule implements Rule
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

        $getID3 = new getID3;

        // the value is an instance of UploadedFile
        $file = $getID3->analyze($value->getRealPath());
        $width = $file['video']['resolution_x'];
        $height = $file['video']['resolution_y'];

        // 檢查比例
        // [1:1],
        $dar = $this->getAspectRatio($width, $height);
        if(!in_array($dar, ['1:1', '16:9'])) {
            $this->message_str = '影片尺寸比率不正確，目前僅接受 [1:1]、[16:9] 的影片。';
            return false;
        }

        // 檢查尺寸
        // 最大 1200 x 1200, 1200 x 628 -- 暫時不需要(大於這尺寸就系統調整即可)
        // 最小 600 x 600, 600 x 314
        if($dar == '1:1' && ($width < 500 || $height < 500 || $width > 1080 || $height > 1080)) {
            $this->message_str = '影片尺寸不正確，目前僅接受 [1,080 >= 寛度 <= 500]、[1,080 >= 高度 <= 500] 的影片。';
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
