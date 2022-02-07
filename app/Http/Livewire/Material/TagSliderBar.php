<?php

namespace App\Http\Livewire\Material;

use App\Models\MaterialTag;
use Livewire\Component;

class TagSliderBar extends Component
{
    public $search = '';

    public $choice_id;

    protected $listeners = ['choiceTag', 'tagReload' => '$refresh'];

    public function choiceTag($tag_id)
    {
        $this->choice_id = $tag_id;
    }


    public function render()
    {
        $items = MaterialTag::where('parent_id', '>', 0);
        if(!empty($this->search))
            $items->where('name', 'LIKE', "%$this->search%");

        return view('livewire.material.tag-slider-bar', [
            'parents' => MaterialTag::where('parent_id', 0)->withCount('materials')->get(),
            'items' => $items->withCount('materials')->get()->groupBy('parent_id'),
        ]);
    }
}
