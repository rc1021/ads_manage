<?php

namespace App\Jobs;

use App\Models\Image as ModelsImage;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\image\ResizeFilter;
use FFMpeg\Filters\image\imageFilters;
use FFMpeg\Format\image\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filters\TileFactory;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;

class ConvertImageForThumbing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $image;

    public function __construct(ModelsImage $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage = Storage::disk($this->image->disk);
        $storage->makeDirectory($this->image->thumbnail_dir_path, 0777);
        $img = Image::make($storage->path($this->image->path));
        // 快照
        $img->resize(320, 320, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($storage->path($this->image->thumbnail_path));

        $this->image->update([
            'converted_for_thumbing_at' => Carbon::now(),
        ]);
    }
}
