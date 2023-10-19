<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    public function index()
    {
        $candidatos = Candidato::select('candidatos.id', 'candidatos.nombre', DB::raw('SUM(candidato_formato.votos) as votos_totales'))
            ->join('candidato_formato', 'candidatos.id', '=', 'candidato_formato.candidato_id')
            ->groupBy('candidatos.id', 'candidatos.nombre')
            ->get();

        // Calcula el total de votos
        $totalVotos = $candidatos->sum('votos_totales');

        // Calcula el porcentaje para cada candidato
        $candidatos->each(function ($candidato) use ($totalVotos) {
            $candidato->porcentaje = ($candidato->votos_totales / $totalVotos) * 100;
        });

        return response()->json(['data' => $candidatos]);
    }

    public function ganador()
    {
        $ganador = Candidato::select('candidatos.id', 'candidatos.nombre')
            ->selectRaw('SUM(candidato_formato.votos) as total_votos')
            ->leftJoin('candidato_formato', 'candidatos.id', '=', 'candidato_formato.candidato_id')
            ->groupBy('candidatos.id', 'candidatos.nombre')
            ->orderBy('total_votos', 'desc') // Ordena por votos en orden descendente
            ->first(); // Obtiene el primer resultado (el candidato con más votos)

        return response()->json(['data' => $ganador]);
    }
}
