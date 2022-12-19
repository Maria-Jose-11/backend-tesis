<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordValidator;
use Illuminate\Auth\Events\PasswordReset;

class PasswordController extends Controller
{

    // Función para el módulo de recuperación de contraseña
    public function resendLink(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->sendResponse(__($status))
            : $this->sendResponse(
                message: 'Link reset failure.',
                errors: ['email' =>__($status)],
                code: 422
            );
    }

    public function redirectReset(Request $request)
    {
        $frontend_url = env('APP_FRONTEND_URL');
        $token = $request->route('token');
        $email = $request->email;
        $url = "$frontend_url/?token=$token&email=$email";
        return $this->sendResponse(message: 'Successful redirection', result: ['url' => $url]);
    }




    // Función para la actualización del password
    public function restore(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request -> validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            // https://laravel.com/docs/9.x/validation#rule-confirmed
            'password' => [
                'required', 'string', 'confirmed',
                // https://laravel.com/docs/9.x/validation#validating-passwords
                PasswordValidator::defaults()->mixedCase()->numbers()->symbols(),
            ],
        ]);


        // Función para cambiar el password
        $status = Password::reset($validated, function ($user , $password)
        {
            $user->password = Hash::make($password);
            $user->save();
            event(new PasswordReset($user)); 
        });

        return $status == Password::PASSWORD_RESET
            ? $this->sendResponse(__($status))
            : $this->sendResponse(
                message: 'Reset password failure.',
                errors: ['email' =>__($status)],
                code: 422
            );
    }

    public function update(Request $request)
    {
        $validated = $request -> validate([
        'password' => ['required', 'string', 'confirmed',
                        PasswordValidator::defaults()->mixedCase()->numbers()->symbols()]]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        return $this->sendResponse('Contraseña actualizada correctamente');
    }


}