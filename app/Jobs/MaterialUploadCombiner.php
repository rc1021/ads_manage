<?php

namespace App\Jobs;

use App\Enums\MaterialStatusType;
use App\Enums\MaterialType;
use App\Models\Material;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;

class MaterialUploadCombiner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $material_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($material_id)
    {
        $this->material_id = $material_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = Material::find($this->material_id);
        if(!$model)
            return ;

        // 取得暫存位置
        $temp_files = Storage::files(Material::GetTempDirectory($model->id));
        sort($temp_files);
        $first_file = array_shift($temp_files);
        // 建立並合併檔案
        $filepath = Storage::putFile(Material::GetPublicDirectory($model->id), new File(Storage::path($first_file)));
        foreach($temp_files as $file) {
            $content = file_get_contents(Storage::path($file));
            if (!file_put_contents(Storage::path($filepath), $content, FILE_APPEND)) {
                throw new Exception(sprintf('file append failed: from "%s" append to "%s"', Storage::path($file), $filepath));
            }
        }
        $extra_data = $model->extra_data;
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Combin;
        // 記錄原檔路徑
        $extra_data['origin'] = array_merge(data_get($extra_data, 'origin', []), [
            'path' => $filepath
        ]);
        $model->extra_data = $extra_data;
        $model->save();
        // 刪除暫存檔
        Storage::deleteDirectory(Material::GetTempDirectory($model->id));
        // 後續處理
        $done_for_method = 'handleFor'.MaterialType::fromValue((int)$model->type)->key;
        if(method_exists($this, $done_for_method))
            $this->{$done_for_method}($model);
    }

    /**
     * Combin之後圖片後續處理
     *
     * @param  mixed $model
     * @return void
     */
    public function handleForImage(Material $model)
    {
        // 建立縮圖
        $t640 = Material::GetPublicDirectory($model->id) . '/t640.png';
        $extra_data = $model->extra_data;
        $filepath = data_get($model->extra_data, 'origin.path', null);
        Image::make(Storage::path($filepath))
            ->resize(640, 640, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(Storage::path($t640));
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Done;
        // 記錄縮圖路徑
        $extra_data['origin'] = array_merge(data_get($extra_data, 'origin', []), [
            'thumnails' => [
                '640' => $t640
            ]
        ]);
        $model->extra_data = $extra_data;
        $model->save();
    }

    /**
     * Combin之後影片後續處理
     *
     * @param  mixed $model
     * @return void
     */
    public function handleForVideo(Material $model)
    {
        // 建立縮圖
        $t640 = Material::GetPublicDirectory($model->id) . '/t640.png';
        $extra_data = $model->extra_data;
        $filepath = data_get($model->extra_data, 'origin.path', null);
        FFMpeg::open($filepath)
            ->getFrameFromSeconds(1)
            ->export()
            ->save($t640);
        Image::make(Storage::path($t640))
            ->resize(640, 640, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(Storage::path($t640));
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Done;
        // 記錄縮圖路徑
        $extra_data['origin'] = array_merge(data_get($extra_data, 'origin', []), [
            'thumnails' => [
                '640' => $t640
            ]
        ]);
        $model->extra_data = $extra_data;
        $model->save();
    }
}
