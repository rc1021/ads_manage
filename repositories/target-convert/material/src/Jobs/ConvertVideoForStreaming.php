<?php

namespace TargetConvert\Material\Jobs;

use TargetConvert\Material\Models\Material;
use TargetConvert\Material\Models\Video;
use Carbon\Carbon;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
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
        $lowBitrate = (new X264)->setKiloBitrate(250);
        $midBitrate = (new X264)->setKiloBitrate(500);
        $highBitrate = (new X264)->setKiloBitrate(1000);

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_pad_path)
            ->exportForHLS()
            ->withRotatingEncryptionKey(function ($filename, $contents) {
                Storage::disk($this->video->disk)->put($this->video->secret_path . $filename, $contents);
            })
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->toDisk($this->video->disk)
            ->save($this->video->m3u8_pad_path);

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_gblur_path)
            ->exportForHLS()
            ->withRotatingEncryptionKey(function ($filename, $contents) {
                Storage::disk($this->video->disk)->put($this->video->secret_path . $filename, $contents);
            })
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->toDisk($this->video->disk)
            ->save($this->video->m3u8_gblur_path);

        $this->video->update([
            'converted_for_streaming_at' => Carbon::now(),
        ]);
    }
}
