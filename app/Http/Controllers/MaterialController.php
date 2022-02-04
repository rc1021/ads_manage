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
    public function index(Request $request, MaterialRepository $rep)
    {
        $model = $rep->getIndexViewModel($request->all());
        // if($request->ajax())
            return response()->json($model);
        // return view('materials.index');
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
            'id' => 'required', // 素材檔在資料庫裡的 id
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:materials,title,'.$material->id.',id',
        ]);

        try {
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            return $rep->update($material, $request->all());
        }
        catch(Exception $e) {
            if($request->ajax())
                return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
            abort(409, $e->getMessage());
        }
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
    }
}
