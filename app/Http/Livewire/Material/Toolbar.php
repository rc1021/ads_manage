<?php

namespace App\Http\Livewire\Material;

use App\Enums\MaterialType;
use Livewire\Component;

class Toolbar extends Component
{
    public $type = MaterialType::Text;

    public $display = 0; // 0: table, 1: picture, 2: grid

    public function changeType($type)
    {
        $this->type = $type;
        $this->emitTo('material.data-list', 'changeType', $type);
    }

    public function changeDisplay($display)
    {
        $this->display = $display;
        $this->emitTo('material.data-list', 'changeDisplay', $display);
    }

    public function dataReload()
    {
        $this->emitTo('material.data-list', 'dataReload');
    }

    public function render()
    {
        return view('livewire.material.toolbar');
    }
}
