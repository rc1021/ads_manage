<?php

namespace App\Models;

use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\ConvertVideoForThumbing;
use Exception;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Video extends Model
{
    use HasFactory, Cachable;

    const DiskSecret = 'secret';
    const DiskStream = 'streamable_videos';
    const DiskDownload = 'downloadable_videos';
    const DiskThumnail = 'thumnail_videos';

    protected $dates = [
        'converted_for_downloading_at',
        'converted_for_streaming_at',
    ];

    protected $guarded = [];

    public function getIsReadyAttribute()
    {
        return !is_null($this->attributes['converted_for_thumbing_at'])
            && !is_null($this->attributes['converted_for_downloading_at'])
            && !is_null($this->attributes['converted_for_streaming_at']);
    }

    public function getThumbnailPathAttribute() { return $this->attributes['id'] . '/thumbnail.png'; }
    public function getThumbnailGifPathAttribute() { return $this->attributes['id'] . '/thumbnails.gif'; }
    public function getThumbnailVttPathAttribute() { return $this->attributes['id'] . '/thumbnails.vtt'; }
    public function getThumbnailTilePathAttribute() { return $this->attributes['id'] . '/thumb_%05d.jpg'; }
    public function getVideoPadPathAttribute() { return $this->attributes['id'] . '/pad.' . $this->attributes['extension']; }
    public function getM3u8PadPathAttribute() { return $this->attributes['id'] . '/pad.m3u8'; }
    public function getVideoGblurPathAttribute() { return $this->attributes['id'] . '/gblur.' . $this->attributes['extension']; }
    public function getM3u8GblurPathAttribute() { return $this->attributes['id'] . '/gblur.m3u8'; }

    public function getThumbnailUrlAttribute() { return Storage::disk(self::DiskThumnail)->url($this->thumbnail_path); }
    public function getThumbnailGifUrlAttribute() { return Storage::disk(self::DiskThumnail)->url($this->thumbnail_gif_path); }
    // public function getThumbnailVttUrlAttribute() { return Storage::disk(self::DiskThumnail)->url($this->thumbnail_vtt_Path); }
    public function getVideoPadUrlAttribute() { return Storage::disk(self::DiskDownload)->url($this->video_pad_path); }
    public function getM3u8PadUrlAttribute() { return Storage::disk(self::DiskStream)->url($this->m3u8_pad_path); }
    public function getVideoGblurUrlAttribute() { return Storage::disk(self::DiskDownload)->url($this->video_gblur_path); }
    public function getM3u8GblurUrlAttribute() { return Storage::disk(self::DiskStream)->url($this->m3u8_gblur_path); }
    public function getThumbnailVttParseAttribute() : array
    {
        $parser = new \Podlove\Webvtt\Parser;
        return $parser->parse(Storage::disk('thumnail_videos')->get($this->attributes['id'].'/thumbnails.vtt'));
    }

    /**
     * 將影片轉換成各式資源
     *
     * @return void
     */
    public function letsConvert($sync = false)
    {
        if($sync) {
            try {
                ConvertVideoForThumbing::dispatchSync($this);
                ConvertVideoForDownloading::dispatchSync($this);
                ConvertVideoForStreaming::dispatchSync($this);
            }
            catch(Exception $e) {
                $this->update([
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else {
            Bus::chain([
                new ConvertVideoForThumbing($this),
                new ConvertVideoForDownloading($this),
                new ConvertVideoForStreaming($this),
            ])
            ->catch(function (Throwable $e) {
                $this->update([
                    'error' => $e->getMessage(),
                ]);
            })
            ->dispatch();
        }
    }

    protected static function booted()
    {
        static::created(function ($video) {
            $video->letsConvert();
        });
    }
}
