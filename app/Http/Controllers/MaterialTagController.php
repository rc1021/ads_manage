<?php

namespace App\Http\Controllers;

use App\Models\MaterialTag;
use App\Models\MaterialTagFolder;
use App\Repositories\MaterialTagRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('material_tags')->where(function ($query) use ($request) {
                    $query
                        ->where('name', $request->name)
                        ->where('folder_id', ($request->folder_id) ? $request->folder_id : 0);
                })
            ],
        ])->validate();

        $model = MaterialTag::create(collect($request->all())->only(['name', 'folder_id'])->toArray());
        session()->flash('success', __('Tag successfully created.'));
        if($request->ajax())
            return response()->json($model);
        return back();
    }

    /**
     * 新增檔案夾
     *
     * @param  mixed $request
     * @return void
     */
    public function fstore(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:material_tag_folders,name',
        ])->validate();

        $model = MaterialTagFolder::create(collect($request->all())->only(['name'])->toArray());
        session()->flash('success', __('Folder successfully created.'));
        if($request->ajax())
            return response()->json($model);
        return back();
    }

    /**
     * 更新檔案夾
     *
     * @param  mixed $request
     * @return void
     */
    public function fupdate(Request $request, MaterialTagFolder $materialTagFolder)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:material_tag_folders,name,' . $materialTagFolder->id,
        ])->validate();

        $materialTagFolder->update(collect($request->all())->only(['name'])->toArray());
        session()->flash('success', __('Folder successfully updated.'));
        if($request->ajax())
            return response()->json($materialTagFolder);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialTag  $materialTag
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialTag $materialTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialTag  $materialTag
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialTag $materialTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialTag  $materialTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialTag $materialTag)
    {
        Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('material_tags')->where(function ($query) use ($request) {
                    $query
                        ->where('name', $request->name)
                        ->where('folder_id', ($request->folder_id) ? $request->folder_id : 0);
                })->ignore($materialTag->id)
            ],
        ])->validate();

        $materialTag->update(collect($request->all())->only(['name', 'folder_id'])->toArray());
        session()->flash('success', __('Material tag successfully updated.'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialTag  $materialTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialTag $materialTag)
    {
        $materialTag->forceDelete();
        session()->flash('success', $materialTag->name . ' ' . __('Tag successfully deleted.'));
        return back();
    }

    public function fdelete(MaterialTagFolder $materialTagFolder)
    {
        $materialTagFolder->load('tags');
        if(count($materialTagFolder->tags) > 0) {
            // 取得 folder_id 是 0 的 tag name
            $duplicates = MaterialTag::where('folder_id', 0)->whereIn('name', $materialTagFolder->tags->pluck('name'))->get()->pluck('name')->all();
            // 不重覆的 tag name 直接改 folder_id
            $materialTagFolder->tags()->whereNotIn('name', $duplicates)->update([
                'folder_id' => 0,
                'timestamps' => false,
            ]);
            // 有重覆的 tag name 加入 folder name 再改 folder_id (逐筆)
            $materialTagFolder->tags->filter(fn ($tag) => in_array($tag->name, $duplicates))->each(function ($tag) use ($materialTagFolder) {
                $tag->name = $materialTagFolder->name . '-' . $tag->name;
                $tag->folder_id = 0;
                $tag->timestamps = false;
                $tag->save();
            });
        }
        $materialTagFolder->forceDelete();
        session()->flash('success', $materialTagFolder->name . ' ' . __('Folder successfully deleted.'));
        return back();
    }
}
