<?php

namespace App\Repositories;

use App\Enums\MaterialType;
use App\Jobs\MaterialUploadCombiner;
use App\Jobs\ProcessMaterialTemporary;
use App\Models\Image;
use App\Models\Material;
use App\Models\MaterialTag;
use App\Models\Video;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MaterialRepository
{
    /**
     * 將媒體存到暫存區
     *
     * @param  mixed $blob
     * @return void
     */
    public function save_to_temp($blob)
    {
        $data = collect(request()->all())->only(['id', 'index', 'total'])->toArray();
        $temp_dir = Material::DirectoryTemporary . $data['id'];
        $number = strlen(floor($data['total'])); // 取得 total 有幾位數
        $blob->storeAs($temp_dir, 'tmp.'.str_pad($data['index'], $number, "0", STR_PAD_LEFT));
        // 所有 part 上傳完成
        if(count(Storage::files($temp_dir)) == $data['total'])
            MaterialUploadCombiner::dispatch($data['id']);
    }

    /**
     * 批次建立素材(自動重新命名)
     *
     * @param  mixed $data
     */
    public function batchCreate(array $input)
    {
        $input = collect($input ?: request()->all());
        $models = collect([]);

        // texts
        if($input->has('texts') && count($items = array_filter($input->get('texts', []))) > 0) {
            foreach ($items as $key => $value) {
                $models->push(Material::createInstance([
                    'title' => $value,
                    'type' => MaterialType::Text,
                ]));
            }
        }
        // temporary
        elseif($input->has('temporaries') && count($items = array_filter($input->get('temporaries', []))) > 0) {
            foreach ($items as $key => $value) {
                $models->push($this->createFromTemporaryID($value));
            }
        }

        // add tags
        if($input->has('tags')) {
            $tags = collect(json_decode($input->get('tags')))
                ->pluck('value')
                ->map(function ($item, $key) {
                    $tag = MaterialTag::firstOrCreate([
                        'name' => $item
                    ]);
                    if($tag->wasRecentlyCreated && $tag->parent_id == 0)
                        $tag->update(['parent_id' => 1]);
                    return $tag;

                })->all();
            $models->each(function ($item, $key) use ($tags) {
                $item->tags()->saveMany($tags);
            });
        }
    }

    /**
     * 新增來自暫存區的素材
     *
     * @param  mixed $temporary_id
     * @return Material
     */
    public function createFromTemporaryID($temporary_id) : Material
    {
        if(!Cache::has($temporary_id))
            throw new Exception('temporary_id not found.');

        $input = Cache::get($temporary_id);
        $metadata = data_get($input, 'metadata', []);
        $title = data_get($metadata, 'name', $temporary_id);
        $type = data_get($input, 'type', MaterialType::Text);
        $model = Material::createInstance(compact('title', 'type'));
        ProcessMaterialTemporary::dispatch($temporary_id, $model);
        return $model;
    }

    /**
     * 更新素材
     *
     * @param  mixed $material
     * @param  mixed $input
     * @return void
     */
    public function update(Material $material, array $input) : Material
    {
        $input = collect($input ?: request()->all())->only(['title', 'tags']);
        $keys = $input->keys();

        // 更名
        if($keys->contains('title')) {
            $material->update($input->only(['title'])->toArray());
        }

        // 更改素材標籤
        if($keys->contains('tags')) {
            $material->tags()->sync($input->get('tags', []));
        }

        return $material;
    }

    /**
     * 素材列表
     *
     * @param  mixed $input
     * @return void
     */
    public function getIndexViewModel(array $input)
    {
        $input = collect($input ?: request()->all())->only([
            'sortby', // 預設排序欄位
            'orderby', // 降序(desc) | 升序(asc)
            'type',
            'pagesize',
        ])->toArray();

        $items = Material::with('tags')
            ->whereIn('type', data_get($input, 'type', array_map('strval', MaterialType::getValues())))
            ->orderby(data_get($input, 'sortby', 'created_at'), data_get($input, 'orderby', 'asc'))
            ->paginate(data_get($input, 'pagesize', 15))
            ->withQueryString();

        return compact('items');
    }
}
