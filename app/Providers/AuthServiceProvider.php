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
        
        Gate::define('manage-superadmin', function (User $user)
        {
            return $user->tipoUsuario->slug === "superadmin";
        });

        Gate::define('manage-admin', function (User $user)
        {
            return $user->tipoUsuario->slug === "superadmin";
        });


    }
}
