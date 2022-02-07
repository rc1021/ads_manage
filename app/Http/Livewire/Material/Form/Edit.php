<?php

namespace App\Http\Livewire\Material\Form;

use App\Models\Material;
use App\Models\MaterialTag;
use Livewire\Component;

class Edit extends Component
{
    public $material; // 正在被編輯的素材

    public $checkboxes = [];

    public $keywords = [];

    public $search;

    public $autocompelte = [];

    protected $listeners = ['edit'];

    protected $messages = [
        'material.title.unique' => 'This field has already been taken.',
    ];

    function rules() {
        if($this->material) {
            return [
                'material.title' => 'required|unique:materials,title,' . $this->material->id,
            ];
        }
        return [];
    }

    private function getTagIds() : array
    {
        return collect(array_keys(array_filter(data_get($this, 'checkboxes', []), fn($v) => $v !== false)))
            ->filter(function ($value, $key) { return $value; })
            ->map(function ($item, $key) {
                $tag = MaterialTag::firstOrCreate([
                    'name' => $item
                ]);
                if($tag->wasRecentlyCreated && $tag->parent_id == 0)
                    $tag->update(['parent_id' => 1]);
                return $tag;
            })->pluck('id')->all();
    }

    public function updatedSearch()
    {
        if(!empty($this->search))
            $this->autocompelte = MaterialTag::where('name', 'LIKE', "%$this->search%")->get()->pluck('name');
        else
            $this->reset(['autocompelte']);
    }

    public function addTag($keyword)
    {
        array_push($this->keywords, $keyword);
        $this->keywords = array_unique($this->keywords);
        $this->checkboxes[$keyword] = true;
        $this->reset(['search', 'autocompelte']);
    }

    public function addTagBySearch()
    {
        $this->addTag($this->search);
    }

    public function edit($id)
    {
        $this->resetMaterial();
        $this->resetErrorBag();
        $material = Material::find($id);
        if($material) {
            $this->material = $material;
            $this->keywords = $material->tags->pluck('name')->toArray();
            $this->checkboxes = array_fill_keys($this->keywords, true);
        }
    }

    public function resetMaterial()
    {
        $this->reset(['checkboxes', 'keywords', 'search', 'autocompelte', 'material']);
    }

    public function save()
    {
        $this->resetErrorBag();
        $this->validate();

        $this->material->save();
        $this->material->tags()->sync($this->getTagIds());

        $this->emitUp('dataReload');
        $this->emitTo('material.tag-slider-bar', 'tagReload');
        $this->reset(['checkboxes', 'keywords', 'search', 'autocompelte', 'material']);
        session()->flash('success', __('Material successfully updated.'));
    }

    public function render()
    {
        return view('livewire.material.form.edit');
    }
}
