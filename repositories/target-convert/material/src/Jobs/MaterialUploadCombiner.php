<?php

namespace TargetConvert\Material\Jobs;

use TargetConvert\Material\Enums\MaterialStatusType;
use TargetConvert\Material\Enums\MaterialType;
use TargetConvert\Material\Enums\TemporaryStatusType;
use TargetConvert\Material\Models\Material;
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

    public $tries = 3;
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
        $metadata = data_get($data, 'metadata', []);

        // 取得暫存位置
        $metadata['disk'] = config('filesystems.default');
        $storage = Storage::disk($metadata['disk']);
        $temp_files = $storage->files(Material::DirectoryTemporary . $this->temporary_id);
        sort($temp_files);
        $first_part = array_shift($temp_files);
        // 建立並合併檔案
        $filepath = $storage->putFile(Material::DirectoryTemporary . $this->temporary_id, new File($storage->path($first_part)));
        foreach($temp_files as $file) {
            $content = file_get_contents($storage->path($file));
            if (!file_put_contents($storage->path($filepath), $content, FILE_APPEND)) {
                throw new Exception(sprintf('file append failed: from "%s" append to "%s"', $storage->path($file), $filepath));
            }
        }
        // 更新狀態
        $metadata['status'] = "".TemporaryStatusType::Combin;
        // 記錄原檔路徑
        $metadata['path'] = $filepath;
        $metadata['mime_type'] = Storage::mimeType($filepath);
        $data['metadata'] = $metadata;
        Cache::put($this->temporary_id, $data);

        // 建立 model for material, and image(or video etc...)
        $arr = explode('/', $metadata['mime_type']);
        $type = array_shift($arr);
        $done_for_method = 'handleForType'.ucfirst($type);
        if(method_exists($this, $done_for_method))
            $this->{$done_for_method}();
    }

    /**
     * Combin之後影片後續處理
     *
     * @return void
     */
    public function handleForTypeVideo()
    {
        $data = Cache::get($this->temporary_id);
        $metadata = data_get($data, 'metadata', []);
        // 記錄時長
        $src = data_get($data, 'metadata.path', null);
        $metadata['time'] = FFMpeg::open($src)->getDurationInSeconds();
        $data['metadata'] = $metadata;
        Cache::put($this->temporary_id, $data);
    }
}
