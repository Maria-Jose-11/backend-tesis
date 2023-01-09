<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Whoops\Run;

class ProfileController extends Controller
{
        
    public function show()
    {
        $user = Auth::user();
        return $this->sendResponse(message: "User's profile returned successfully", result: [
            'user' => new ProfileResource($user),
            'avatar' => $user->getAvatarPath()
        ]);
    }

    // función para actualizar los datos del usuario
    public function store(Request $request)
    {
        // Validar que el usuario sea mayor de edad
        $allowed_date_range =[
            'max' => date('Y-m-d', strtotime('-70 years')),
            'min' => date('Y-m-d', strtotime('-18 years')),
        ];

        // Validación de los datos de entrada
        $request -> validate([
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string', 'min:3', 'max:35'],
        ]);

        // Se obtiene el modelo del usuario
        $user = $request->user();
        // Se actualiza el modelo en la BDD
        // https://laravel.com/docs/9.x/queries#update-statements
        $user->update($request->all());
        // Se invoca a la función padre
        return $this->sendResponse('Profile updated successfully');
    }


}