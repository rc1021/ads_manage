<?php

namespace TargetConvert\Material\Models;

use TargetConvert\Material\Jobs\ConvertImageForThumbing;
use TargetConvert\Material\Traits\HasMediaType;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Image extends Model
{
    use HasFactory, HasMediaType;

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.image_table'));
        parent::__construct($attributes);
    }

    protected $dates = [
        'converted_for_thumbing_at',
    ];

    protected $guarded = [];

    public function getThumbnailDirPathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/'; }
    public function getThumbnailPathAttribute() { return $this->thumbnail_dir_path . 'thumbnail.' . $this->attributes['extension']; }

    public function getThumbnailUrlAttribute() { return Storage::disk('public')->url($this->thumbnail_path); }

    public function material()
    {
        return $this->morphOne(Material::class, 'mediaable');
    }

    /**
     * 將圖片快照
     *
     * @return void
     */
    public function letsConvert($sync = false)
    {
        if($sync) {
            try {
                ConvertImageForThumbing::dispatchSync($this);
            }
            catch(Exception $e) {
                $this->update([
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else {
            Bus::chain([
                new ConvertImageForThumbing($this),
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
        static::created(function ($image) {
            $image->letsConvert();
        });
    }
}
