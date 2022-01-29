<?php

namespace App\Http\Controllers;

use App\Enums\MaterialType;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'title' => 'required',
            'type' => ['required', new EnumValue(MaterialType::class, false)],
        ]);

        try {
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            $model = $rep->create($request->all());
            if($request->ajax())
                return response()->json($model);
            return redirect()->route('materials.index');
        }
        catch(Exception $e) {
            if($request->ajax())
                return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
            abort(409, $e->getMessage());
        }
    }

    public function upload(Request $request, MaterialRepository $rep)
    {
        Validator::make($request->all(), [
            'data' => 'required|file',
            'id' => 'required',
            'name' => 'required',
            'total' => 'required',
            'index' => 'required',
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
        //
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
    public function update(Request $request, Material $material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        //
    }
}
