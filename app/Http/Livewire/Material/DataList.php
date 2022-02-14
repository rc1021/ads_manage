<?php

namespace App\Http\Livewire\Material;

use App\Enums\MaterialType;
use App\Models\Material;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class DataList extends Component
{
    use WithPagination;

    public $delete_id;

    public $choice_id;

    public $sortby_col;

    public $orderby = false; // true: desc, false: asc

    public $trash_flag = false;

    public $type = MaterialType::Text;

    protected $display_views = [
        MaterialType::Text => 'text',
        MaterialType::Image => 'grid',
        MaterialType::Video => 'grid',
    ];

    protected $listeners = ['changeType', 'changeDisplay', 'choiceTag', 'dataSort', 'dataReload' => '$refresh'];

    public function choiceTag($tag_id)
    {
        $this->resetPage();
        $this->choice_id = ($tag_id > 0) ? $tag_id : null;
        $this->trash_flag = ($tag_id === -1);
        if($this->trash_flag) {
            $this->sortby_col = 'deleted_at';
            $this->orderby = true;
        }
    }

    public function changeType($type)
    {
        $this->resetPage();
        $this->type = $type;
        $this->emitTo('material.form.edit', 'edit', null);
    }

    public function changeDisplay($display)
    {
        $this->display = $display;
        $this->emitTo('material.form.edit', 'edit', null);
    }

    public function dataSort($col)
    {
        if($this->sortby_col != $col) {
            $this->sortby_col = $col;
            $this->orderby = false;
        }
        else {
            $this->orderby = !$this->orderby;
        }
    }

    public function restore($id = null)
    {
        $model = Material::withTrashed()->find($id);
        if($model) {
            $model->restore();
            session()->flash('success', __('Material successfully restore.'));
        }
    }

    public function deleteID($id = null)
    {
        $this->delete_id = $id;
    }

    public function delete($event)
    {
        if(data_get($event, 'isTrusted', false)) {
            Material::find($this->delete_id)->delete();
            $this->delete_id = null;
            session()->flash('success', __('Material successfully delete.'));
            return ;
        }
    }

    public function resetSortBy()
    {
        $this->sortby_col = null;
        $this->orderby = false;
    }

    public function render()
    {
        $items = Material::with('tags')->withCount('tags')->where('type', "".$this->type);
        if ($this->trash_flag) {
            $items->onlyTrashed();
        }
        else if($this->choice_id > 0) {
            $items->whereHas('tags', function (Builder $query) {
                $query->where('id', $this->choice_id);
            });
        }
        if($this->sortby_col) {
            $items->orderBy($this->sortby_col, ($this->orderby) ? 'desc' : 'asc');
        }
        return view('livewire.material.data-list.'.$this->display_views[$this->type], [
            'items' => $items->paginate(50),
        ]);
    }
}
