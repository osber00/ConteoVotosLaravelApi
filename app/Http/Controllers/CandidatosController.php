<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CandidatosController extends Controller
{
    public function index()
    {
        $candidatos = Candidato::all();
        return response()->json([
            'data' => $candidatos,
            'estado' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        $candidato = Candidato::find($id);
        if ($candidato != null){
            $rules = [
                'numero' => 'required|string|max:2',
                'nombre' => 'required|string|max:50',
            ];

            $this->validate($request, $rules);

            $existe = Candidato::where('id','<>',$candidato->id)
                ->where('slug',Str::slug($request->nombre))
                ->orWhere('numero',$request->numero)
                ->exists();
            if (!$existe){
                $candidato->update([
                    'numero' => $request->numero,
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre)
                ]);
                return response()->json([
                    'data'=>$candidato,
                    'estado' => 'updated'
                ]);
            }else{
                return response()->json([
                    'data'=> null,
                    'estado' => 'duplicate'
                ]);
            }
        }else{
            return response()->json([
                'data'=> null,
                'estado' => 'void'
            ]);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'numero' => 'required|string|max:2|unique:candidatos',
            'nombre' => 'required|string|max:50',
        ];

        $this->validate($request, $rules);

        $existe = Candidato::where('slug',Str::slug($request->nombre))->orWhere('numero',$request->numero)->exists();

        if (!$existe){
            $candidato =Candidato::create([
                'numero' => $request->numero,
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre)
            ]);

            return response()->json([
                'data' => $candidato,
                'estado' => 'created'
            ]);
        }else{
            return response()->json([
                'data'=> null,
                'estado' => 'duplicate'
            ]);
        }



    }

    public function delete($id)
    {
        $candidato = Candidato::destroy($id);
        return response()->json([
            'data' => $candidato,
            'estado' => 'delete'
        ]);
    }
}
