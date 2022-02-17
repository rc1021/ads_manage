<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForDownloading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

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
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            ->export()
            ->addFilter(['-vf', 'scale=1080:1080:force_original_aspect_ratio=decrease,pad=1080:1080:(ow-iw)/2:(oh-ih)/2'])
            ->toDisk($this->video->disk)
            ->save($this->video->video_pad_path);

        $opener = FFMpeg::fromDisk($this->video->disk)->open($this->video->path);
        $dimensions = $opener->getVideoStream()->getDimensions();
        $dar = $dimensions->getRatio()->getValue();
        $filter = 'split [main][tmp]; [main]scale=1920:1920:force_original_aspect_ratio=decrease [main]; [tmp] crop=(ih/16*9):ih,scale=iw/10:-2,gblur=sigma=5,scale=1920:1920 [vbg]; [vbg][main] overlay=0:(H-h)/2,scale=1080:1080:force_original_aspect_ratio=decrease';
        if($dar < 1)
            $filter = 'split [main][tmp]; [main]scale=1920:1920:force_original_aspect_ratio=decrease [main]; [tmp] crop=iw:(iw/16*9),scale=-2:ih/10,gblur=sigma=5,scale=1920:1920 [vbg]; [vbg][main] overlay=(W-w)/2:0,scale=1080:1080:force_original_aspect_ratio=decrease';
        $opener
            ->export()
            ->addFilter(['-filter_complex', $filter])
            ->toDisk($this->video->disk)
            ->save($this->video->video_gblur_path);

        $this->video->update([
            'converted_for_downloading_at' => Carbon::now(),
        ]);
    }
}
