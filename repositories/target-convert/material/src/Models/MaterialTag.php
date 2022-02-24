<?php

namespace TargetConvert\Material\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Support\Collection;

class MaterialTag extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.material_tag_table'));
        parent::__construct($attributes);
    }

    protected $guarded = [];

    /**
     * 取得此標籤底下的文案
     *
     * @return void
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, app(MaterialTagMaterial::class)->getTable());
    }

    /**
     * 取得此標籤上一層標籤
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(MaterialTagFolder::class, 'folder_id');
    }

    /**
     * 取得所有標籤並依 parent_id 為群組
     *
     * @return Collection
     */
    public static function GetGroupByParentID() : Collection
    {
        return self::get()->groupBy('folder_id');
    }
}
