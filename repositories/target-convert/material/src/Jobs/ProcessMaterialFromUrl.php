<?php

namespace TargetConvert\Material\Jobs;

use TargetConvert\Material\Enums\MaterialStatusType;
use TargetConvert\Material\Enums\MaterialType;
use TargetConvert\Material\Models\Material;
use TargetConvert\Material\Models\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Embera\Embera;
use Embera\ProviderCollection\CustomProviderCollection;
use Illuminate\Support\Facades\Storage;

class ProcessMaterialFromUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, Material $model = null)
    {
        $this->url = $url;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 目前僅提供 youtube
        // $embera = new Embera;
        $provider = new CustomProviderCollection();
        $provider->registerProvider([
            'Youtube'
        ]);
        $embera = new Embera([], $provider);
        $arrUrlData = $embera->getUrlData([$this->url]);

        $url = new Url;
        $filesystem_default_disk = config('filesystems.default');
        $url->disk = $filesystem_default_disk;
        $url->id = app('snowflake')->id();
        if(array_key_exists($this->url, $arrUrlData) && $metadata = $arrUrlData[$this->url])
        {
            $url->title = $metadata['title'];
            $url->metadata = $metadata;
            if($thumbnail_url = data_get($metadata, 'thumbnail_url', false)) {
                $contents = file_get_contents($thumbnail_url);
                $url->path = $url->thumbnail_path;
                Storage::disk($filesystem_default_disk)->put($url->path, $contents);
            }
        }
        else {
            $url->error = '目前暫時無法解析 ' . $this->url;
        }
        $url->save();

        $this->model->type = "" . MaterialType::Video;
        $this->model->mediaable_id = $url->id;
        $this->model->mediaable_type = get_class($url);
        $this->model->status_type = "".MaterialStatusType::Done;
        $this->model->save();
    }
}
