<?php

namespace App\Http\Controllers;

use App\Enums\MaterialType;
use App\Models\Material;
use App\Models\MaterialTag;
use App\Repositories\MaterialRepository;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input('type', MaterialType::Text);
        // level 0 tags
        $tag_parents = MaterialTag::where('parent_id', 0)->withCount('materials')->get();
        // level 1 tags
        $tags = MaterialTag::where('parent_id', '>', 0)->withCount('materials')->get()->groupBy('parent_id');
        // choice tag
        $tag = MaterialTag::find($request->input('tid'));
        // the level 1 array of tag name
        $tag_names = MaterialTag::where('id', '>', 1)->pluck('name')->toJson();
        // relation items of the choice tag
        $items = Material::done()->with('tags', 'mediaable')->withCount('tags')->where('type', ''.$type);
        if ($search = $request->input('search')) {
            $items->where('title', 'LIKE', "%$search%");
        }
        if ($request->input('tid') < 0)
            $items->onlyTrashed();
        else if($tag && $tag->id > 0)
            $items->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('id', $tag->id);
            });
        $items = $items->paginate(50);
        // if($this->sortby_col) {
        //     $items->orderBy($this->sortby_col, ($this->orderby) ? 'desc' : 'asc');
        // }
        return view('materials.index', compact('type', 'tag_parents', 'tags', 'tag', 'tag_names', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MaterialRepository $rep)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        try {
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            $data = $rep->batchCreate($request->all());
            if($request->ajax())
                return response()->json($data);
            return redirect()->route('materials.index');
        }
        catch(Exception $e) {
            if($request->ajax())
                return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
            abort(409, $e->getMessage());
        }
    }

    /**
     * 上傳部份素材至暫存空間，等到全部內容下載完畢後，開始執行合併
     *
     * @param  mixed $request
     * @param  mixed $rep
     * @return void
     */
    public function upload(Request $request, MaterialRepository $rep)
    {
        Validator::make($request->all(), [
            'data' => 'required|file',
            'total' => 'required',
            'index' => 'required',
            'id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if(!Cache::has($value))
                        $fail(__('The temporary id is invalid'));
                }
            ], // 驗證 temporary id 是否存在
        ])->validate();

        if ($request->file('data')->isValid())
            $rep->save_to_temp($request->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        $material->load('tags');
        // if(request()->ajax())
            return response()->json($material);
        // return view('materials.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material, MaterialRepository $rep)
    {
        Validator::make($request->all(), [
            'title' => 'required|unique:materials,title,'.$material->id.',id',
        ])->validate();
        $rep->update($material, $request->all());
        session()->flash('success', __('Material successfully updated.'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        $material->delete();
        session()->flash('success', __('Material successfully delete.'));
        return back();
    }
}
