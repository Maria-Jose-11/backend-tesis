<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Emprendimiento;
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
        Emprendimiento::class => ReportPolicy::class,
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
        Gate::define('manage-superadmin', function (User $user)
        {
            return $user->tipoUsuario->slug === "superadmin";
        });
        // El usuario con perfil admin solo puede realizar la
        // gestión (CRUD) de guardias
        Gate::define('manage-admin', function (User $user)
        {
            return $user->tipoUsuario->slug === "superadmin";
        });
        // El usuario con perfil admin solo puede realizar la
        // gestión (CRUD) de prisioneros
        Gate::define('manage-prisoners', function (User $user)
        {
            return $user->tipoUsuario->slug === "admin";
        });


    }
}
