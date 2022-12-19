<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');

// Rutas para modulo de recuperación de contraseña
Route::post('/forgot-password', [PasswordController::class, 'resendLink'])->name('password.resend-link');
Route::get('/reset-password/{token}', [PasswordController::class, 'redirectReset'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'restore'])->name('password.restore');

//Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum'])->group(function ()
{
    // Ruta para el cierre de sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Ruta para la actualización de contraseña del usuario
    Route::post('/update-password', [PasswordController::class, 'update'])->name('password.update');

});


