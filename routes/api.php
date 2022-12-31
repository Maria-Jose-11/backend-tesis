<?php

use App\Http\Controllers\Account\AvatarController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\SuperadminController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function ()
{
    // Hacer uso del archivo auth.php
    require __DIR__ . '/auth.php';


    Route::middleware(['auth:sanctum'])->group(function ()
    {
    
        Route::prefix('profile')->group(function ()
        {
            Route::controller(ProfileController::class)->group(function ()
            {
                Route::get('/', 'show')->name('profile');
                Route::post('/', 'store')->name('profile.store');
            });
            Route::post('/avatar', [AvatarController::class, 'store'])->name('profile.avatar');
        });

        Route::prefix("superadmin")->group(function ()
        {
            Route::controller(SuperadminController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'store');
                Route::get('/{user}', 'show');
                Route::post('/{user}/update', 'update');
                Route::get('/{user}/destroy', 'destroy');
            });
        });

        Route::prefix("admin")->group(function ()
        {
            Route::controller(AdminController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'store');
                Route::get('/{user}', 'show');
                Route::post('/{user}/update', 'update');
                Route::get('/{user}/destroy', 'destroy');
            });
        });

        Route::prefix('emprendimiento')->group(function () {
            Route::controller(EmprendimientoController::class)->group(function ()
            {
                Route::get('/', 'index');
                Route::post('/create', 'store');
                Route::get('/{emprendimiento}', 'show');
                Route::post('/{emprendimiento}/update', 'update');
                Route::get('/{epmrendimiento}/destroy', 'destroy');
            });
        });


    });
});


