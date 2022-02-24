<?php

namespace TargetConvert\Material\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTagFolder extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.material_tag_folder_table'));
        parent::__construct($attributes);
    }

    protected $guarded = [];
    public $timestamps = false;

    public function tags()
    {
        return $this->hasMany(MaterialTag::class, 'folder_id');
    }
}
