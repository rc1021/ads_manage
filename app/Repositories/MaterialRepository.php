<?php

namespace App\Repositories;

use App\Jobs\MaterialUploadCombiner;
use App\Models\Material;
use Exception;
use Illuminate\Database\QueryException;
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
}
