<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTagFolder extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function tags()
    {
        return $this->hasMany(MaterialTag::class, 'folder_id');
    }
}
