<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registro(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:200',
            'email' => 'required|string|email|max:100|unique:users',
            'identificacion' => 'required|string|max:10|unique:users'
        ];

        $this->validate($request,$rules);

        $usuario = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'identificacion' => $request->identificacion,
            'password' => bcrypt($request->identificacion)
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $usuario,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
