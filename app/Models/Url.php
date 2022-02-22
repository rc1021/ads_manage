<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Url extends Model
{
    use HasFactory;

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
