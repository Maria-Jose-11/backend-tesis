<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\EmprendimientoImagenController;
use App\Http\Controllers\VideoConferenciaController;
use Illuminate\Support\Facades\Route;

// Ruta pública para el manejo de inicio de sesión del usuario
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Ruta pública para el manejo del olvido de contraseña del usuario
Route::post('/forgot-password', [PasswordController::class, 'resendLink'])->name('password.resend-link');

// Ruta pública para la redirección del formulario y actualizar los datos
Route::get('/reset-password/{token}', [PasswordController::class, 'redirectReset'])->name('password.reset');

// Ruta pública para el manejo del reseteo de la contraseña del usuario
Route::post('/reset-password', [PasswordController::class, 'restore'])->name('password.restore');

//Route::get('registration', [AuthController::class, 'registration'])->name('register-user');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::prefix('emprendimiento')->group(function () {
    Route::controller(EmprendimientoController::class)->group(function ()
    {
        Route::get('/', 'index');
        Route::post('/create', 'store');
    });
    Route::post('/{emprendimiento}/logo', [EmprendimientoImagenController::class, 'store'])->name('emprendimiento.logo');
});

Route::prefix('videoconferencia')->group(function () {
    Route::controller(VideoConferenciaController::class)->group(function ()
    {
        Route::get('/', 'index');
        
    });
});

// Grupo de rutas protegidas
Route::middleware(['auth:sanctum'])->group(function ()
{
    // Ruta para el cierre de sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Ruta para el cambio de contraseña del usuario
    Route::post('/update-password', [PasswordController::class, 'update'])->name('password.update');

});


