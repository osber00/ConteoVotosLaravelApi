<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name','asc')->get();
        return response()->json(['data'=>$usuarios, 'estado'=>'success']);
    }

    public function formatosusuario($id)
    {
        $usuario = User::where('id',$id)->with('formatos','formatos.lugar')->first();
        return response()->json(['data'=>$usuario, 'estado'=>'success']);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:200',
            'identificacion' => 'required|min:5|max:10|unique:users,identificacion',
        ];

        $this->validate($request, $rules);

        $usuario = User::create([
            'name' => $request->nombre,
            'email' => $request->identificacion.'@example.com',
            'identificacion' => $request->identificacion,
            'password' => bcrypt($request->identificacion)
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $usuario,
            'token' => $token,
            'estado' => 'created'
        ]);

    }

    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        $rules = [
            'nombre' => 'required|string|min:3|max:200',
            'identificacion' => ['required',
                'min:5',
                'max:10',
                Rule::unique('users', 'identificacion')->ignore($usuario->id),
            ],
        ];

        $this->validate($request, $rules);

        $usuario->update([
            'name' => $request->nombre,
            'identificacion' => $request->identificacion,
            'email' => $request->identificacion.'@example.com',
            'password' => bcrypt($request->identificacion)
        ]);

        return response()->json([
            'data' => $usuario,
            'estado' => 'updated'
        ]);
    }
}
