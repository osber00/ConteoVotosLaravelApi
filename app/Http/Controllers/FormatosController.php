<?php

namespace App\Http\Controllers;

use App\Models\Formato;
use App\Models\User;
use Illuminate\Http\Request;

class FormatosController extends Controller
{
    public function index()
    {
        $formatos = Formato::with('lugar','candidatos')->get();
        return response()->json(['data'=>$formatos, 'estado'=>'success']);
    }

    public function edit($id)
    {
        $data = Formato::with('lugar','candidatos')
            ->where('id',$id)
            ->first();
        return response()->json(['data'=>$data]);
    }

    public function store(Request $request)
    {
        $rules = [
            'user' => 'required|exists:users,id',
            'lugar' => 'required|exists:lugars,id',
            'mesa' => 'required'
        ];

        $this->validate($request, $rules);

        $existe = Formato::where([
            'user_id' => $request->user,
            'lugar_id' => $request->lugar,
            'mesa' => $request->mesa,
        ])->exists();

        if (!$existe){
            $formato = Formato::create([
                'user_id' => $request->user,
                'lugar_id' => $request->lugar,
                'mesa' => $request->mesa
            ]);

            return response()->json([
                'data' => $formato,
                'estado' => 'created'
            ]);
        }else{
            return response()->json([
                'data'=> null,
                'estado' => 'duplicate'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $formato = Formato::find($id);
        if ($formato != null){
            $rules = [
                'user' => 'required|exists:users,id',
                'lugar' => 'required|exists:lugars,id',
                'mesa' => 'required',
                'sufragantes' => 'required',
                'urnas' => 'required',
                'incinerados' => 'required',

            ];

            $this->validate($request, $rules);

            $existe = Formato::where('id','<>',$id)->where([
                'user_id' => $request->user,
                'lugar_id' => $request->lugar,
                'mesa' => $request->mesa
            ])->exists();

            if (!$existe){
                $formato->update([
                    'user_id' => $request->user,
                    'lugar_id' => $request->lugar,
                    'mesa' => $request->mesa,
                    'sufragantes' => $request->sufragantes,
                    'urnas' => $request->urnas,
                    'incinerados' => $request->incinerados,
                ]);

                return response()->json([
                    'data' => $formato,
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
}
