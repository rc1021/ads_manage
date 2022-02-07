<?php

namespace App\Http\Livewire\Material\Form;

use App\Enums\MaterialType;
use App\Models\MaterialTag;
use App\Repositories\MaterialRepository;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Item extends Component
{
    public $count = 4;

    public $tags;

    public $texts = [null];

    public $images = [];

    public $videos = [];

    public $type = MaterialType::Text;

    public $modal = false;

    protected $listeners = ['refreshAll' => '$refresh'];

    protected $rules = [
        'texts.*' => 'required|unique:materials,title',
    ];

    protected $messages = [
        'texts.*.required' => 'This field field is required.',
        'texts.*.unique' => 'This field has already been taken.',
    ];

    public function changeType($type)
    {
        $this->type = $type;
    }

    public function addImage($temporary_id)
    {
        array_push($this->images, $temporary_id);
    }

    public function removeImage($temporary_id)
    {
        $this->images = array_values(array_filter($this->images, fn ($m) => $m != $temporary_id));
    }

    public function addVideo($temporary_id)
    {
        array_push($this->videos, $temporary_id);
    }

    public function removeVideo($temporary_id)
    {
        $this->videos = array_values(array_filter($this->videos, fn ($m) => $m != $temporary_id));
    }

    public function addInput()
    {
        array_push($this->texts, null);
    }

    public function removeInput($key)
    {
        unset($this->texts[$key]);
        $this->texts = array_values($this->texts);
    }

    private function getTagModels() : array
    {
        return $this->getTagCellection()->map(function ($item, $key) {
            $tag = MaterialTag::firstOrCreate([
                'name' => $item
            ]);
            if($tag->wasRecentlyCreated && $tag->parent_id == 0)
                $tag->update(['parent_id' => 1]);
            return $tag;
        })->all();
    }

    private function getTagCellection() : Collection
    {
        try {
            return collect(json_decode($this->tags))->pluck('value');
        }
        catch(Exception) {
            return collect([]);
        }
    }

    public function update(MaterialRepository $rep)
    {
        $type = MaterialType::fromValue((int)$this->type);

        if($type->is(MaterialType::Text)) {
            $this->validate();
            $tags = $this->getTagModels();
            foreach($this->texts as $text) {
                $material = $rep->create([
                    'title' => $text,
                    'type' => $this->type
                ]);
                if($material)
                    $material->tags()->saveMany($tags);
            }
        }
        else {
            $temporary_ids = [];
            if($type->is(MaterialType::Image)) {
                if(count($this->images) == 0) {
                    session()->flash('error', __('No items upload'));
                    return ;
                }
                $temporary_ids = $this->images;
            }
            else if($type->is(MaterialType::Video)) {
                if(count($this->videos) == 0) {
                    session()->flash('error', __('No items upload'));
                    return ;
                }
                $temporary_ids = $this->videos;
            }
            else {
                $this->reset(['tags', 'texts', 'images', 'videos', 'modal']);
                return ;
            }

            $tags = $this->getTagModels();
            foreach($temporary_ids as $temporary_id) {
                try {
                    $material = $rep->createFromTemporaryID($temporary_id);
                    if($material)
                        $material->tags()->saveMany($tags);
                }
                catch(Exception $e) {
                    session()->flash('warning', __($e->getMessage()));
                }
            }
        }

        $this->emitTo('material.data-list', 'dataReload');
        $this->emitTo('material.tag-slider-bar', 'tagReload');
        $this->emitSelf('refreshAll');
        $this->reset(['tags', 'texts', 'images', 'videos', 'modal']);
        session()->flash('success', __('Materials successfully created.'));
    }

    public function render()
    {
        return view('livewire.material.form.item', [
            'posts' => MaterialTag::all()->pluck('name')->all()
        ]);
    }
}
