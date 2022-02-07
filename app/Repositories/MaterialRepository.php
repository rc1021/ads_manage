<?php

namespace App\Repositories;

use App\Enums\MaterialType;
use App\Jobs\MaterialUploadCombiner;
use App\Models\Material;
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
        $input = collect(request()->all())->only(['id', 'index', 'name', 'total']);
        $data = $input->toArray();

        $number = strlen(floor($data['total']));
        $data['index'] = str_pad($data['index'], $number, "0", STR_PAD_LEFT);

        $temp = Material::GetTempDirectory($data['id']);
        $blob->storeAs($temp, 'tmp.'.$data['index'].'.'.$data['name']);

        $files = Storage::files($temp);
        if(count($files) == $data['total'])
            MaterialUploadCombiner::dispatch($data['id']);
    }

    /**
     * 建立素材或返回一個空值(自動重新命名)
     *
     * @param  mixed $data
     * @return Material
     */
    public function create(array $input) : Material
    {
        $input = collect($input ?: request()->all())->only(['title', 'type', 'extra_data']);
        return Material::createInstance($input->toArray());
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
        $input['title'] = data_get($input, 'extra_data.origin.name', $temporary_id);
        $model = $this->create($input);

        Cache::forget($temporary_id);
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
