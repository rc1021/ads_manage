<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filters\TileFactory;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;

class ConvertVideoForThumbing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $interval = 5;
        $disk = Video::DiskThumnail;
        $opener = FFMpeg::fromDisk($this->video->disk)->open($this->video->path);
        $time = $opener->getDurationInSeconds();
        $dimensions = $opener->getVideoStream()->getDimensions();
        $dar = $dimensions->getRatio()->getValue();
        // 設定快照上限寛高
        $width = 320; $height = $dimensions->getRatio()->calculateHeight($width);
        if($dar < 1) {
            $height = 320;
            $width = $dimensions->getRatio()->calculateWidth($height);
        }

        // 建立vtt快照
        // https://support.jwplayer.com/articles/how-to-add-preview-thumbnails
        // https://packagist.org/packages/podlove/webvtt-parser

        $opener->exportTile(function (TileFactory $factory) use ($interval, $time, $width, $height) {
                // 每張快照最多 5 欄
                $cols = 5;
                $rows = 1;
                $total = ceil($time / $interval);
                if($total <= $cols)
                    $cols = $total;
                else
                    $rows = ceil($total / $cols);
                // 每張快照最多 5 列
                if($rows > 5)
                    $rows = 5;
                $factory->interval($interval)
                    ->scale($width, $height)
                    ->grid($cols, $rows)
                    ->generateVTT($this->video->thumbnail_vtt_path);
            })
            ->toDisk($disk)
            ->save($this->video->thumbnail_tile_path);
        Storage::disk($disk)->append($this->video->thumbnail_vtt_path, "\n");

        // 建立動畫
        $media = $opener->getDriver()->get(); // ProtoneMedia\LaravelFFMpeg\FFMpeg\VideoMedia
        if($media instanceof \ProtoneMedia\LaravelFFMpeg\FFMpeg\VideoMedia) {
            $media->gif(
                TimeCode::fromSeconds(0),
                new Dimension($width, $height),
                ($time >= 5) ? 5 : $time
            )->save(Storage::disk($disk)->path($this->video->thumbnail_gif_path));
        }

        // 建立快照
        $opener
            ->getFrameFromSeconds(($time > 0) ? 1 : 0)
            ->export()
            ->toDisk($disk)
            ->save($this->video->thumbnail_path);

        $path = Storage::disk($disk)->path($this->video->thumbnail_path);
        $img = Image::make($path);
        $img->resize(320, 320, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($path);

        $this->video->update([
            'time' => $time,
            'converted_for_thumbing_at' => Carbon::now(),
        ]);
    }
}
