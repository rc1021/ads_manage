<?php

namespace App\Http\Livewire\Material;

use App\Models\MaterialTag;
use Livewire\Component;

class TagSliderBar extends Component
{
    public $search = '';

    public $choice_id;

    public $delete_id;

    public $tag_id;

    public $name;

    public $parent_id;

    public $is_edit = false;

    protected $listeners = ['choiceTag', 'tagReload' => '$refresh'];

    function rules() {
        if($this->tag_id) {
            return [
                'name' => 'required|unique:material_tags,name,' . $this->tag_id,
                'parent_id' => [
                    function ($attribute, $value, $fail) {
                        if($value == $this->tag_id) {
                            $fail('tag can not move to self.');
                            return ;
                        }
                        $tag = MaterialTag::withCount('children')->find($this->tag_id);
                        if ($value && $tag->children_count > 0) {
                            $fail($tag->name.' has children(' . $tag->children_count . ').');
                            return ;
                        }
                    }
                ],
            ];
        }
        return [];
    }

    public function choiceTag($tag_id)
    {
        $this->choice_id = $tag_id;
    }

    public function resetTag()
    {
        $this->reset('tag_id', 'name', 'parent_id');
    }

    public function deleteID($id = null)
    {
        $this->delete_id = $id;
    }

    public function delete($event)
    {
        if(data_get($event, 'isTrusted', false)) {
            $tag = MaterialTag::find($this->delete_id);
            MaterialTag::whereIn('id', $tag->children->pluck('id')->toArray())->update(['parent_id' => 1]);
            $tag->forceDelete();
            $this->reset('delete_id');
            $this->emitTo('material.data-list', 'dataReload');
            session()->flash('success', __('Tag successfully delete.'));
            return ;
        }
    }

    public function edit(MaterialTag $tag)
    {
        $this->resetErrorBag();
        $this->tag_id = $tag->id;
        $this->name = $tag->name;
        $this->parent_id = $tag->parent_id;
    }

    public function update()
    {
        $this->resetErrorBag();
        $this->validate();
        $tag = MaterialTag::find($this->tag_id);
        $tag->name = $this->name;
        $tag->parent_id = $this->parent_id;
        $tag->save();

        $this->reset('tag_id', 'name', 'parent_id');
        $this->emitSelf('tagReload');
        $this->emitTo('material.data-list', 'dataReload');
        session()->flash('success', __('Tag successfully updated.'));
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
