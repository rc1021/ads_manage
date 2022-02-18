<?php

namespace App\Models;

use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\ConvertVideoForThumbing;
use App\Traits\HasMediaType;
use Exception;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Video extends Model
{
    use HasFactory, Cachable, HasMediaType;

    protected $dates = [
        'converted_for_thumbing_at',
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

    public function getThumbnailPathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/thumbnail.png'; }
    public function getThumbnailGifPathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/thumbnails.gif'; }
    public function getThumbnailVttPathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/thumbnails.vtt'; }
    public function getThumbnailTilePathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/thumb_%05d.jpg'; }
    public function getVideoPadPathAttribute() { return Material::DirectoryDownload . $this->attributes['id'] . '/pad.' . $this->attributes['extension']; }
    public function getM3u8PadPathAttribute() { return Material::DirectoryStreamable . $this->attributes['id'] . '/pad.m3u8'; }
    public function getVideoGblurPathAttribute() { return Material::DirectoryDownload . $this->attributes['id'] . '/gblur.' . $this->attributes['extension']; }
    public function getM3u8GblurPathAttribute() { return Material::DirectoryStreamable . $this->attributes['id'] . '/gblur.m3u8'; }
    public function getSecretPathAttribute() { return Material::DirectorySecret; }

    public function getThumbnailUrlAttribute() { return Storage::disk('public')->url($this->thumbnail_path); }
    public function getThumbnailGifUrlAttribute() { return Storage::disk('public')->url($this->thumbnail_gif_path); }
    public function getThumbnailVttUrlAttribute() { return Storage::disk('public')->url($this->thumbnail_vtt_path); }
    public function getVideoPadUrlAttribute() { return Storage::disk('public')->url($this->video_pad_path); }
    public function getM3u8PadUrlAttribute() { return Storage::disk('public')->url($this->m3u8_pad_path); }
    public function getVideoGblurUrlAttribute() { return Storage::disk('public')->url($this->video_gblur_path); }
    public function getM3u8GblurUrlAttribute() { return Storage::disk('public')->url($this->m3u8_gblur_path); }
    public function getPublicThumbnailVttParseAttribute() : array
    {
        $vtt = $this->thumbnail_vtt_parse;
        if(array_key_exists('cues', $vtt)) {
            $vtt['cues'] = collect($vtt['cues'])->map(function ($item) {
                $item['text'] = Storage::disk('public')->url($item['text']);
                return $item;
            });
        }
        return $vtt;
    }
    public function getThumbnailVttParseAttribute() : array
    {
        try {
            $parser = new \Podlove\Webvtt\Parser;
            return $parser->parse(Storage::get($this->thumbnail_vtt_path));
        }
        catch(Exception $e) {}
        return [];
    }

    public function material()
    {
        return $this->morphOne(Material::class, 'mediaable');
    }

    /**
     * 將影片轉換成各式資源
     *
     * @return void
     */
    public function letsConvert($sync = false, $video = false)
    {
        if($sync) {
            try {
                ConvertVideoForThumbing::dispatchSync($this);
                if($video) {
                    ConvertVideoForDownloading::dispatchSync($this);
                    ConvertVideoForStreaming::dispatchSync($this);
                }
            }
            catch(Exception $e) {
                $this->update([
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else {
            $jobs = [new ConvertVideoForThumbing($this)];
            if($video) {
                array_push($jobs, new ConvertVideoForDownloading($this));
                array_push($jobs, new ConvertVideoForStreaming($this));
            }
            Bus::chain($jobs)->catch(function (Throwable $e) {
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
