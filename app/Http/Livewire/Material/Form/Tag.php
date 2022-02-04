<?php

namespace App\Http\Livewire\Material\Form;

use App\Models\MaterialTag;
use Livewire\Component;

class Tag extends Component
{
    public $name = '';

    public $parent_id = 0;

    public $modal = false;

    protected $rules = [
        'name' => 'required',
    ];

    public function update()
    {
        $this->validate();

        MaterialTag::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);

        // after done
        $this->emitTo('material.tag-slider-bar', 'tagReload');
        $this->reset(['modal', 'name', 'parent_id']);
        session()->flash('success', __('Tag successfully created.'));
    }

    public function render()
    {
        return view('livewire.material.form.tag', [
            'parents' => MaterialTag::where('parent_id', 0)->get()
        ]);
    }
}
