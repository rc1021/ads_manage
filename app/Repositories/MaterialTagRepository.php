<?php

namespace App\Repositories;

use App\Models\MaterialTag;

class MaterialTagRepository
{

    /**
     * 建立素材標籤
     *
     * @param  mixed $input
     * @return MaterialTag
     */
    public function create(array $input) : MaterialTag
    {
        $input = collect($input ?: request()->all())->only(['name', 'parent_id']);
        return MaterialTag::create($input->toArray());
    }

}
