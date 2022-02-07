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
use Illuminate\Support\Facades\Cache;

class MaterialUploadCombiner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $temporary_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($temporary_id)
    {
        $this->temporary_id = $temporary_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = Cache::get($this->temporary_id);
        if(!$data)
            return ;

        // 取得額外資訊
        $extra_data = data_get($data, 'extra_data', []);

        // 取得暫存位置
        $temp_files = Storage::files(Material::GetTempDirectory($this->temporary_id));
        sort($temp_files);
        $first_file = array_shift($temp_files);
        // 建立並合併檔案
        $filepath = Storage::putFile(Material::GetPublicDirectory($this->temporary_id), new File(Storage::path($first_file)));
        foreach($temp_files as $file) {
            $content = file_get_contents(Storage::path($file));
            if (!file_put_contents(Storage::path($filepath), $content, FILE_APPEND)) {
                throw new Exception(sprintf('file append failed: from "%s" append to "%s"', Storage::path($file), $filepath));
            }
        }
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Combin;
        // 記錄原檔路徑
        $extra_data['origin'] = array_merge(data_get($extra_data, 'origin', []), [
            'path' => $filepath,
            'mime_type' => Storage::mimeType($filepath),
        ]);

        $data['extra_data'] = $extra_data;
        Cache::put($this->temporary_id, $data);
        // 刪除暫存檔
        Storage::deleteDirectory(Material::GetTempDirectory($this->temporary_id));
        // 後續處理
        $done_for_method = 'handleFor'.MaterialType::fromValue((int)data_get($data, 'type'))->key;
        if(method_exists($this, $done_for_method))
            $this->{$done_for_method}();
    }

    /**
     * Combin之後圖片後續處理
     *
     * @return void
     */
    public function handleForImage()
    {
        $data = Cache::get($this->temporary_id);
        $extra_data = data_get($data, 'extra_data', []);
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Done;
        $data['extra_data'] = $extra_data;
        Cache::put($this->temporary_id, $data);
    }

    /**
     * Combin之後影片後續處理
     *
     * @return void
     */
    public function handleForVideo()
    {
        $data = Cache::get($this->temporary_id);
        $extra_data = data_get($data, 'extra_data', []);
        // 記錄時長
        $src = data_get($data, 'extra_data.origin.path', null);
        $extra_data['time'] = FFMpeg::open($src)->getDurationInSeconds();
        // 更新狀態
        $extra_data['status'] = MaterialStatusType::Done;
        $data['extra_data'] = $extra_data;
        Cache::put($this->temporary_id, $data);
    }
}
