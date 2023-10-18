<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LugaresController extends Controller
{
    public function index()
    {
        $lugares = Lugar::with('formatos')->orderBy('nombre','asc')->get();
        return response()->json(['data'=>$lugares, 'estado'=>'success']);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:200'
        ];

        $this->validate($request, $rules);

        $existe = Lugar::where('slug', Str::slug($request->nombre))->exists();
        if (!$existe){
            $lugar = Lugar::create([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre)
            ]);

            return response()->json([
                'data' => $lugar,
                'estado' => 'created'
            ]);
        }else{
            return response()->json([
                'data' => null,
                'estado' => 'duplicate'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $lugar = Lugar::find($id);
        if ($lugar != null){
            $rules = [
                'nombre' => 'required|string|min:3|max:200',
            ];

            $this->validate($request,$rules);

            $existe = Lugar::where('slug', Str::slug($request->nombre))->where('id','<>',$lugar->id)->exists();

            if (!$existe){
                $lugar->update([
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre)
                ]);
                return response()->json([
                    'data'=> $lugar,
                    'estado' => 'updated'
                ]);
            }else{
                return response()->json([
                    'data' => null,
                    'estado' => 'duplicate'
                ]);
            }

        }
        else{
            return response()->json([
                'data'=> null,
                'estado' => 'void'
            ]);
        }
    }

    public function delete($id)
    {
        $lugar = Lugar::destroy($id);
        return response()->json([
            'data' => $lugar,
            'estado' => 'delete'
        ]);
    }
}
