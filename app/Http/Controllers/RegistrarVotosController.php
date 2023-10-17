<?php

namespace App\Http\Controllers;

use App\Models\Formato;
use Illuminate\Http\Request;

class RegistrarVotosController extends Controller
{
    public function storeUpdate(Request $request, $formatoId)
    {
        $request->validate([
            'votos' => 'required|array',
            'votos.*' => 'required|integer',
        ]);


        $formato = Formato::find($formatoId);
        // Guardamos los votos para cada candidato
        foreach ($request->votos as $candidatoId => $votos) {
            if ($formato->candidatos->contains($candidatoId)) {
                $formato->candidatos()->updateExistingPivot($candidatoId, ['votos' => $votos]);
            } else {
                $formato->candidatos()->attach($candidatoId, ['votos' => $votos]);
            }
        }

        return response()->json([
            'data' => $formato,
            'estado' => 'createdUpdate'
        ]);
    }
}
