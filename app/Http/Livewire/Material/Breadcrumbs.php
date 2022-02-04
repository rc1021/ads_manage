<?php

namespace App\Http\Livewire\Material;

use App\Models\MaterialTag;
use Livewire\Component;

class Breadcrumbs extends Component
{
    public $tag;

    public $parent_title;

    protected $listeners = ['choiceTag'];

    public function choiceTag($tag_id)
    {
        $this->tag = MaterialTag::find($tag_id);
        $this->parent_title = data_get($this->tag, 'parent.name', ($tag_id == -1) ? __('material trash') : null);
    }

    public function render()
    {
        return view('livewire.material.breadcrumbs', [
            'parent' => $this->parent_title,
            'model' => $this->tag,
        ]);
    }
}
