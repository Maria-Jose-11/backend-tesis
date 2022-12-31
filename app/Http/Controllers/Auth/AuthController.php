<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private $discarded_role_names = ['visitante'];


    public function login(Request $request)
    {
        $request -> validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request['email'])->first();

        if (!$user || !$user->state || in_array($user->tipoUsuario->slug, $this->discarded_role_names) ||
            !Hash::check($request['password'], $user->password))
            {
                return $this->sendResponse(message: 'Las credenciales ingresadas son incorrectas.', code: 404);
            }

        if (!$user->tokens->isEmpty())
        {
            return $this->sendResponse(message: 'El usuario ya se encuentra autenticado.', code: 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->sendResponse(message: 'Autenticación exitosa.', result: [
            // https://laravel.com/docs/9.x/eloquent-resources#resource-responses
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    //FUNCION PAA REGISTRAR USUARIOS
    public function register(Request $request)
    {  
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'tipo_usuario_id' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'tipo_usuario_id' => $data['tipo_usuario_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
          ]);

        //$token = $user->createToken('auth-token')->plainTextToken;

        $response = [
            'user'=>$user,
        ];
        return response($response, 201);
    }
 
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse(message: 'Sesión cerrada.');
    }
}