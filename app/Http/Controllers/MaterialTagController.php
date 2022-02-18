<?php

namespace App\Http\Controllers;

use App\Models\MaterialTag;
use App\Repositories\MaterialTagRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
    public function store(Request $request, MaterialTagRepository $rep)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:material_tags',
            // 'parent_id' => 'exists:App\Models\MaterialTag,id',
        ]);

        try {
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            $model = $rep->create($request->all());
            if($request->ajax())
                return response()->json($model);
            return back();
        }
        catch(Exception $e) {
            if($request->ajax())
                return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
            abort(409, $e->getMessage());
        }
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
            'name' => 'required|unique:material_tags,name,' . $materialTag->id,
            'parent_id' => [
                function ($attribute, $value, $fail) use ($materialTag) {
                    if($value == $materialTag->id) {
                        $fail('tag can not move to self.');
                        return ;
                    }
                    $materialTag->loadCount('children');
                    if ($value && $materialTag->children_count > 0) {
                        $fail($materialTag->name.' has children(' . $materialTag->children_count . ').');
                        return ;
                    }
                }
            ],
        ])->validate();

        $materialTag->update(collect($request->all())->only(['name', 'parent_id'])->toArray());
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
        if($materialTag->drop) {
            MaterialTag::whereIn('id', $materialTag->children->pluck('id')->toArray())->update(['parent_id' => 1]);
            $materialTag->forceDelete();
            session()->flash('success', $materialTag->name . ' ' . __('Tag successfully deleted.'));
            return back();
        }
        session()->flash('error', $materialTag->name . ' ' . __('Tag can not delete.'));
        return back();
    }
}
