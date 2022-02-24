<?php

namespace TargetConvert\Material\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Url extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.url_table'));
        parent::__construct($attributes);
    }

    protected $guarded = [];

    protected $casts = [
        'metadata' => AsArrayObject::class,
    ];

    protected $dates = [
        'converted_for_thumbing_at',
    ];

    public function getThumbnailPathAttribute() { return Material::DirectoryThumnail . $this->attributes['id'] . '/thumbnail.png'; }
    public function getThumbnailUrlAttribute() { return Storage::disk('public')->url($this->thumbnail_path); }
}
