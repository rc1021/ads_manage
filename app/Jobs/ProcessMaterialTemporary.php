<?php

namespace App\Jobs;

use App\Enums\MaterialStatusType;
use App\Enums\MaterialType;
use App\Enums\TemporaryStatusType;
use App\Models\Image;
use App\Models\Material;
use App\Models\Video;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use IntlException;
use Throwable;

class ProcessMaterialTemporary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 25;
    protected $temporary_id;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($temporary_id, Material $model)
    {
        $this->temporary_id = $temporary_id;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->model->update([
                'status_type' => "".MaterialStatusType::ProcessMaterialTemporary,
            ]);

            $input = Cache::get($this->temporary_id);
            $metadata = data_get($input, 'metadata', []);
            if(intval(data_get($metadata, 'status', 0)) != TemporaryStatusType::Combin)
                throw new class('temporary is not combin') extends IntlException {};

            $title = data_get($metadata, 'name', $this->temporary_id);
            $ext   = data_get($metadata, 'ext');
            $size  = data_get($metadata, 'size');
            $path  = data_get($metadata, 'path');

            $filesystem_default_disk = config('filesystems.default');
            $storage = Storage::disk($filesystem_default_disk);
            $filldata = [
                'id'            => $this->temporary_id,
                'disk'          => $filesystem_default_disk,
                'original_name' => $title,
                'extension'     => $ext,
                'path'          => $path,
                'title'         => str_replace('.'.$ext, '', $title),
                'size'          => $size,
            ];

            if((int)$this->model->type == MaterialType::Image) {
                $filldata['path'] = Material::DirectoryImage . $this->temporary_id . '/' . $title;
                $storage->move($path, $filldata['path']);
                $image = Image::create($filldata);
                $this->model->mediaable_id = $image->id;
                $this->model->mediaable_type = get_class($image);
            }
            elseif((int)$this->model->type == MaterialType::Video) {
                $filldata['path'] = Material::DirectoryVideo . $this->temporary_id . '/' . $title;
                $storage->move($path, $filldata['path']);
                $video = Video::create($filldata);
                $this->model->mediaable_id = $video->id;
                $this->model->mediaable_type = get_class($video);
            }

            // 變更狀態
            $this->model->status_type = "".MaterialStatusType::Done;
            $this->model->save();

            // 刪除暫存檔
            Storage::deleteDirectory(Material::DirectoryTemporary . $this->temporary_id);
            Cache::forget($this->temporary_id);

        } catch (Throwable $exception) {
            $this->release(1);
            return;
        }
    }
}
