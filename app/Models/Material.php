<?php

namespace App\Models;

use App\Enums\MaterialStatusType;
use App\Enums\MaterialType;
use Exception;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'type', 'extra_data'];

    protected $casts = [
        'extra_data' => AsArrayObject::class,
    ];

    /**
     * 給予 id 取得暫存目錄
     *
     * @param  mixed $id
     * @return void
     */
    public static function GetTempDirectory($id)
    {
        return config('material.temporary').'/'.$id;
    }

    /**
     * 給予 id 取得公開目錄
     *
     * @param  mixed $id
     * @return void
     */
    public static function GetPublicDirectory($id)
    {
        return config('material.public').'/'.$id;
    }

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
        $data['extra_data'] = array_merge(data_get($data, 'extra_data', []), [
            'status' => MaterialType::fromValue($type)->is(MaterialType::Text) ? MaterialStatusType::Done : MaterialStatusType::NotYet
        ]);
        // 重新命名最大次數
        $max_try_times = 10;
        do {
            try {
                return Material::create($data);
            }
            catch(QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if($errorCode != 1062)
                    throw $e;
                // continue, if duplicate entry problem
                $data['title'] = sprintf('[%s]%s', date('Y-m-d H:i:s'), $title);
                // -- or --
                // list($usec, $sec) = explode(" ", microtime());
                // $data['title'] = sprintf('%f%s', (float)$usec + (float)$sec, data_get($input, 'title'));
            }
        } while(--$max_try_times > 0);
        throw new Exception('duplicate entry');
    }

    /**
     * 取得文案標籤
     *
     * @return void
     */
    public function tags()
    {
        return $this->belongsToMany(MaterialTag::class, app(MaterialTagMaterial::class)->getTable());
    }

}
