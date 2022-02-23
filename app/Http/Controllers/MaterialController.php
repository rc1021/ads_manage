<?php

namespace App\Http\Controllers;

use App\Enums\MaterialType;
use App\Models\Material;
use App\Models\MaterialTag;
use App\Models\MaterialTagFolder;
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
        $tag_parents = MaterialTagFolder::withCount('tags')->orderBy('name')->get();
        $tag_folder = new MaterialTagFolder();
        $tag_folder->id = 0;
        $tag_folder->name = '未分類';
        $tag_parents->prepend($tag_folder);
        // level 1 tags
        $tags = MaterialTag::withCount('materials')->orderBy('name')->get()->groupBy('folder_id');
        // choice tag
        $tag = MaterialTag::find($request->input('tid'));
        // the level 1 array of tag name
        $tag_names = MaterialTag::where('id', '>', 1)->orderBy('id', 'desc')->pluck('name')->toJson();
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
        $items = $items->orderBy('id', 'desc')->paginate(20);
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
        $data = $rep->batchCreate($request->all());
        if(count($data) > 0) {
            session()->flash('success', __('Material successfully created.'));
        }
        if($request->ajax())
            return response()->json($data);
        return redirect()->route('materials.index');
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
        ])->after(function ($validator) use ($rep) {
            if($validator->errors()->any())
                $rep->clear_temporary();
        })->validate();

        if ($request->file('data')->isValid()) {
            $result = $rep->upload_temp($request->data);
            if(!is_null($result)) {
                // done and ...
            }
        }
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

    public function restore(Material $material)
    {
        $material->restore();
        session()->flash('success', __('Material successfully restored.'));
        if(request()->ajax())
            return ;
        return back();
    }
}
