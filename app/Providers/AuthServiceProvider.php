<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // https://laravel.com/docs/9.x/authorization#writing-gates

        // El usuario con perfil admin solo puede realizar la
        // gestión (CRUD) de directores
        
        //EN EL CAS0 DEL  PROYECTO, SOLO LOS SUPERADMINS
        //PUEDEN GESTIONAR EL CRUD DE  USUARIOS
        Gate::define('manage-superadmin', function (User $user)
        {
            return $user->tipoUsuario->nombre === "superadmin";
        });

        Gate::define('manage-admin', function (User $user)
        {
            return $user->tipoUsuario->nombre === "superadmin";
        });
        // El usuario con perfil admin solo puede realizar la
        // gestión (CRUD) de guardias

        //INTENTAR
        //TANTO EL USUARIO SUPERADMIN COMO ADMIN PUEDEN
        //GESTIONAR  LOS EMPRENDIMIENTOS
        // Gate::define('manage-emprendimientos', function (User $user)
        // {
        //     return $user->tipoUsuario->nombre === "superadmin";
        //     //return $user->tipoUsuario->nombre === "admin";
        // });

        // El usuario con perfil admin solo puede realizar la
        // gestión (CRUD) de prisioneros
        // Gate::define('manage-prisoners', function (User $user)
        // {
        //     return $user->role->slug === "admin";
        // });


    }
}
