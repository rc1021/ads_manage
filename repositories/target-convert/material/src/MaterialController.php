<?php

namespace TargetConvert\Material;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use TargetConvert\Material\Enums\MaterialType;
use TargetConvert\Material\Enums\TemporaryStatusType;
use TargetConvert\Material\Models\Material;
use TargetConvert\Material\Models\MaterialTag;
use TargetConvert\Material\Models\MaterialTagFolder;
use TargetConvert\Material\Repositories\MaterialRepository;

class MaterialController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function snowflake (Request $request)
    {
        $key = null;
        while(is_null($key) || Cache::has($key)) {
            $key = app('snowflake')->id();
        }
        Cache::put($key, array_merge([
            'status' => TemporaryStatusType::NotYet
        ], $request->all()));
        return response()->json([
            'key' => $key,
            'payload' => Cache::get($key),
        ]);
    }

    public function item_index(Request $request)
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
        $tag_names = MaterialTag::orderBy('id', 'desc')->pluck('name')->toJson();
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
        return view('materials::items.index', compact('type', 'tag_parents', 'tags', 'tag', 'tag_names', 'items'));
    }

    public function item_store(Request $request, MaterialRepository $rep)
    {
        $data = $rep->batchCreate($request->all());
        if(count($data) > 0) {
            material_success(__('Material successfully created.'));
        }
        if($request->ajax())
            return response()->json($data);
        return redirect()->route('material.items.index');
    }

    public function item_upload(Request $request, MaterialRepository $rep)
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

    public function item_update(Request $request, Material $item, MaterialRepository $rep)
    {
        Validator::make($request->all(), [
            'title' => 'required|unique:materials,title,'.$item->id.',id',
        ])->validate();
        $rep->update($item, $request->all());
        material_success(__('Material successfully updated.'));
        return back();
    }

    public function item_destroy(Material $item)
    {
        $item->delete();
        material_success(__('Material successfully delete.'));
        return back();
    }

    public function item_restore(Material $item)
    {
        $item->restore();
        material_success(__('Material successfully restored.'));
        if(request()->ajax())
            return ;
        return back();
    }

    public function tag_store(Request $request)
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
        material_success(__('Tag successfully created.'));
        if($request->ajax())
            return response()->json($model);
        return back();
    }

    public function tag_update(Request $request, MaterialTag $tag)
    {
        Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('material_tags')->where(function ($query) use ($request) {
                    $query
                        ->where('name', $request->name)
                        ->where('folder_id', ($request->folder_id) ? $request->folder_id : 0);
                })->ignore($tag->id)
            ],
        ])->validate();

        $tag->update(collect($request->all())->only(['name', 'folder_id'])->toArray());
        material_success(__('Material tag successfully updated.'));
        return back();
    }

    public function tag_destroy(MaterialTag $tag)
    {
        $tag->forceDelete();
        material_success($tag->name . ' ' . __('Tag successfully deleted.'));
        return back();
    }

    public function folder_store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:material_tag_folders,name',
        ])->validate();

        $model = MaterialTagFolder::create(collect($request->all())->only(['name'])->toArray());
        material_success(__('Folder successfully created.'));
        if($request->ajax())
            return response()->json($model);
        return back();
    }

    public function folder_update(Request $request, MaterialTagFolder $folder)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:material_tag_folders,name,' . $folder->id,
        ])->validate();

        $folder->update(collect($request->all())->only(['name'])->toArray());
        material_success(__('Folder successfully updated.'));
        if($request->ajax())
            return response()->json($folder);
        return back();
    }

    public function folder_destroy(MaterialTagFolder $folder)
    {
        $folder->load('tags');
        if(count($folder->tags) > 0) {
            // 取得 folder_id 是 0 的 tag name
            $duplicates = MaterialTag::where('folder_id', 0)->whereIn('name', $folder->tags->pluck('name'))->get()->pluck('name')->all();
            // 不重覆的 tag name 直接改 folder_id
            $folder->tags()->whereNotIn('name', $duplicates)->update([
                'folder_id' => 0,
            ], ['timestamps' => false]);
            // 有重覆的 tag name 加入 folder name 再改 folder_id (逐筆)
            $folder->tags->filter(fn ($tag) => in_array($tag->name, $duplicates))->each(function ($tag) use ($folder) {
                $tag->name = $folder->name . '-' . $tag->name;
                $tag->folder_id = 0;
                $tag->timestamps = false;
                $tag->save();
            });
        }
        $folder->forceDelete();
        material_success($folder->name . ' ' . __('Folder successfully deleted.'));
        return back();
    }
}
