<?php

namespace App\Http\Livewire\Material\Form;

use App\Enums\MaterialType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Item extends Component
{
    use WithFileUploads;

    public $tags;

    public $texts = [null];

    public $images = [];

    public $type = MaterialType::Text;

    public $modal = false;

    protected $rules = [
        'texts.*' => 'unique:materials,title',
        'images.*' => 'image|max:512', // 1MB Max
    ];

    protected $messages = [
        'texts.*.unique' => 'This field has already been taken.',
    ];

    public function changeType($type)
    {
        $this->type = $type;
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

    public function updatedImages ()
    {
        $this->validate();
    }

    public function update()
    {
        $this->validate();

        $type = MaterialType::fromValue((int)$this->type);
        if($type->is(MaterialType::Text)) {

        }
        else if($type->is(MaterialType::Image)) {

        }
        else if($type->is(MaterialType::Video)) {

        }
        else {
            return ;
        }

        $this->reset(['tags', 'texts', 'modal']);
        session()->flash('success', __('Materials successfully created.'));
    }

    public function render()
    {
        return view('livewire.material.form.item');
    }
}
