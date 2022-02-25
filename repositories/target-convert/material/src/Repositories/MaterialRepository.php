<?php

namespace TargetConvert\Material\Repositories;

use TargetConvert\Material\Enums\MaterialType;
use TargetConvert\Material\Jobs\MaterialUploadCombiner;
use TargetConvert\Material\Jobs\ProcessMaterialFromUrl;
use TargetConvert\Material\Jobs\ProcessMaterialTemporary;
use TargetConvert\Material\Models\Image;
use TargetConvert\Material\Models\Material;
use TargetConvert\Material\Models\MaterialTag;
use TargetConvert\Material\Models\Video;
use TargetConvert\Material\Rules\MaterialImageRule;
use TargetConvert\Material\Rules\MaterialVideoRule;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\File;

class MaterialRepository
{
    /**
     * 將媒體存到暫存區
     *
     * @param  mixed $blob
     * @return void
     */
    public function upload_temp($blob)
    {
        $data = collect(request()->all())->only(['id', 'index', 'total'])->toArray();
        $temp_dir = Material::DirectoryTemporary . $data['id'];
        $number = strlen(floor($data['total'])); // 取得 total 有幾位數
        $blob->storeAs($temp_dir, 'tmp.'.str_pad($data['index'], $number, "0", STR_PAD_LEFT));
        // 所有 part 上傳完成, 要驗證格式是否允許上傳
        if(count(Storage::files($temp_dir)) == $data['total'])
        {
            $job = new MaterialUploadCombiner($data['id']);
            $job->handle();
            $this->validate_temp($data['id']);
        }
        return null;
    }

    /**
     * 驗證上傳內容
     *
     * @param  mixed $temporary_id
     * @return void
     */
    public function validate_temp($temporary_id)
    {
        $data = Cache::get($temporary_id);
        $storage = Storage::disk(data_get($data, 'metadata.disk', config('filesystems.default')));
        $file = new File($storage->path(data_get($data, 'metadata.path')));
        $mime_type = Str::of(data_get($data, 'metadata.mime_type'))->lower();

        // image
        if(Str::startsWith($mime_type, Str::of(MaterialType::getKey(MaterialType::Image))->lower()))
        {
            Validator::make(compact('file'), [
                'file' => [
                    'bail',
                    'required',
                    'mimes:jpg,gif,png',
                    sprintf('max:%s', 5 * 1024), // KB, max: 5MB
                    new MaterialImageRule,
                ],
            ])->after(function ($validator) {
                if($validator->errors()->any())
                    $this->clear_temporary();
            })->validate();
        }
        // video
        elseif(Str::startsWith($mime_type, Str::of(MaterialType::getKey(MaterialType::Video))->lower()))
        {
            Validator::make(compact('file'), [
                'file' => [
                    'bail',
                    'required',
                    'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
                    sprintf('max:%s', 4 * 1024 * 1024), // KB, max: 4GB
                    new MaterialVideoRule,
                ],
            ])->after(function ($validator) {
                if($validator->errors()->any())
                    $this->clear_temporary();
            })->validate();
        }
    }

    /**
     * 移除 temporary 的快取、暫存檔案
     *
     * @return void
     */
    public function clear_temporary($temporary_id = null)
    {
        $temporary_id = $temporary_id ?: request()->input('id');
        $data = Cache::get($temporary_id);
        if(!$data)
            return ;

        // 取得額外資訊
        $metadata = data_get($data, 'metadata', []);
        $storage = Storage::disk($metadata['disk']);
        // 刪除合併檔
        if($storage->exists($metadata['path']))
            $storage->delete($metadata['path']);
        // 刪除暫存
        Storage::deleteDirectory(Material::DirectoryTemporary . $temporary_id);
        // 刪除快取
        Cache::forget($temporary_id);
    }

    /**
     * 批次建立素材(自動重新命名)
     *
     * @param  mixed $input
     * @return array
     */
    public function batchCreate(array $input) : array
    {
        $input = collect($input ?: request()->all());
        $models = collect([]);

        // texts
        if($input->has('texts') && count($items = array_filter($input->get('texts', []))) > 0) {
            foreach ($items as $key => $value) {
                $models->push(Material::createInstance([
                    'title' => $value,
                    'type' => "".MaterialType::Text,
                ]));
            }
        }
        // temporary
        if($input->has('temporaries') && count($items = array_filter($input->get('temporaries', []))) > 0) {
            foreach ($items as $key => $value) {
                $models->push($this->createFromTemporaryID($value));
            }
        }
        // urls
        if($input->has('urls') && count($urls = array_filter(explode(PHP_EOL, $input->get('urls', '')))) > 0) {
            foreach ($urls as $key => $value) {
                $models->push($this->createFromUrl($value));
            }
        }

        // add tags
        if($input->has('tags')) {
            $tags = $this->getTags(collect(json_decode($input->get('tags')))->pluck('value'));
            $models->each(function ($item, $key) use ($tags) {
                $item->tags()->saveMany($tags);
            });
        }

        return $models->all();
    }

    /**
     * 新增來自Url的素材
     *
     * @param  mixed $temporary_id
     * @return Material
     */
    public function createFromUrl($url) : Material
    {
        $key = app('snowflake')->id();
        $title = $key . '-' . Str::limit($url, 23 - strlen($key));
        $type = "".MaterialType::Video;
        $model = Material::createInstance(compact('title', 'type'));
        ProcessMaterialFromUrl::dispatch($url, $model);
        return $model;
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
        $mime_type = data_get($metadata, 'mime_type');
        $arr = explode('/', $metadata['mime_type']);
        $type = "".MaterialType::getValue(array_shift($arr));
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
            $material->tags()->sync($this->getTagIds($input->get('tags', [])));
        }

        return $material;
    }

    private function getTags($tags = []) : array
    {
        return collect($tags)->map(function ($item, $key) {
                $tag = MaterialTag::firstOrCreate([
                    'name' => $item
                ]);
                // if($tag->wasRecentlyCreated && $tag->folder_id == 0)
                //     $tag->update(['folder_id' => 0]);
                return $tag;
            })->all();
    }

    private function getTagIds($tags = []) : array
    {
        return collect($this->getTags($tags))->pluck('id')->all();
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
