<?php

namespace App\Traits;

use App\Enums\MaterialType;
use App\Models\Material;
use App\Models\MaterialTag;
use Exception;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

trait MaterialThumnail
{
    public static function bootMaterialThumnail() {
        // 確保使用 trait 是 Material
        if(static::class != Material::class) {
            throw new Exception("Only ".Material::class." trait.");
        }
    }

    /**
     * 取得縮圖主鍵
     *
     * @param  mixed $width
     * @param  mixed $height
     * @return void
     */
    private function getThumnailKey(int $width = 640, int $height = null)
    {
        return ($height) ? $width."x".$height : "".$width;
    }

    /**
     * 取得縮圖路徑
     *
     * @param  mixed $width
     * @param  mixed $height
     * @return void
     */
    public function getThumnailUrl(int $width = 640, int $height = null)
    {
        if(data_get($this->extra_data, 'status') != 2)
            return null;

        // 縮圖主鍵
        $key = $this->getThumnailKey($width, $height);
        $extra_data = $this->extra_data;

        // 返回已存在的縮圖路徑
        if($thumnail = data_get($extra_data, 'thumnails.'.$key, null))
            return Storage::url($thumnail);

        // 返回新創立的縮圖路徑
        if($thumnail = $this->makeThumnail($width, $height))
            return Storage::url($thumnail);

        return null;
    }

    /**
     * 圖片縮放
     *
     * @param  mixed $width             寛
     * @param  mixed $height            長
     * @param  mixed $filter            過濾器
     * @param  mixed $throwException    遇到錯誤是否拋出
     * @return void
     */
    public function makeThumnail(int $width = 640, int $height = null, \Closure $filter = null, bool $throwException = false)
    {
        try {
            // 縮圖主鍵
            $key = $this->getThumnailKey($width, $height);
            $extra_data = $this->extra_data;
            $target = sprintf("%s/%s_%s.png", Material::GetPublicDirectory($this->id), $width, $height ?: $width);

            $src = data_get($extra_data, 'origin.path', null);
            if(MaterialType::fromValue((int)$this->type)->is(MaterialType::Video())) {
                FFMpeg::open($src)->getFrameFromSeconds(1)->export()->save($target);
                $src = $target;
            }

            $filter = $filter ?: function ($constraint) {
                $constraint->aspectRatio();
            };

            Image::make(Storage::path($src))
                ->resize($width, $height, function ($constraint) use ($filter) { $filter($constraint); })
                ->save(Storage::path($target));

            // to public
            Storage::copy($target, 'public/'.$target);

            // 記錄縮圖路徑
            $extra_data['thumnails'] = array_merge(data_get($extra_data, 'thumnails', []), [
                $key => $target,
            ]);
            $this->extra_data = $extra_data;
            $this->save();

            return $target;
        }
        catch(Exception $e) {
            if($throwException)
                throw $e;
        }
        return null;
    }
}
