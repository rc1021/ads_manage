<?php

namespace TargetConvert\Material\Models;

use TargetConvert\Material\Enums\MaterialStatusType;
use TargetConvert\Material\Enums\MaterialType;
use TargetConvert\Material\Traits\MaterialThumnail;
use Exception;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class Material extends Model
{
    use SoftDeletes, Cachable, MaterialThumnail;

    const DirectoryTemporary  = 'materials/tmp_materials/';
    const DirectoryFeed  = 'materials/feeds/';
    const DirectoryAudio = 'materials/audios/';
    const DirectoryImage = 'materials/images/';
    const DirectoryVideo = 'materials/videos/';
    const DirectoryDownload = 'materials/downloadable/';
    const DirectorySecret = 'materials/secret/';
    const DirectoryStreamable = 'materials/streamable/';
    const DirectoryThumnail = 'materials/thumnail/';

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('material.database.material_table'));
        parent::__construct($attributes);
    }

    protected $guarded = [];

    protected $casts = [
        'extra_data' => AsArrayObject::class,
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * 建立素材, 自動重新命名
     *
     * @param  mixed $data
     * @return Material
     */
    public static function createInstance($data = []) : Material
    {
        // 素材名稱
        $title = data_get($data, 'title', __('unnamed'));
        // 取得素材類型
        $type = (int)data_get($data, 'type', MaterialType::Text);
        // 設定狀態
        $data['status_type'] = ($type == MaterialType::Text) ? "".MaterialStatusType::Done : "".MaterialStatusType::NotYet;
        // 重新命名最大次數
        $max_try_times = 10;
        do {
            try {
                return self::create($data);
            }
            catch(QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if($errorCode != 1062)
                    throw $e;
                // continue, if duplicate entry problem
                $data['title'] = sprintf('[%s]%s', app('snowflake')->id(), $title);
                // -- or --
                // list($usec, $sec) = explode(" ", microtime());
                // $data['title'] = sprintf('%f%s', (float)$usec + (float)$sec, data_get($input, 'title'));
            }
        } while(--$max_try_times > 0);
        throw new Exception('duplicate entry');
    }

    public function scopeDone($query)
    {
        return $query->where('status_type', ''.MaterialStatusType::Done);
    }

    public function mediaable()
    {
        return $this->morphTo();
    }

    /**
     * 取得文案標籤
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(MaterialTag::class, app(MaterialTagMaterial::class)->getTable());
    }

}
