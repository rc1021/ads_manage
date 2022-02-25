<?php

namespace TargetConvert\Material\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialTagMaterial extends Model
{

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.material_tag_material_table'));
        parent::__construct($attributes);
    }
}
