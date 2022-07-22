<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Policies\AdminPolicy;
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

        
        Gate::define('isadmin', [AdminPolicy::class, 'isAdmin']);
        Gate::define('crear_inc', [AdminPolicy::class, 'crearInc']);
        Gate::define('carga_masiva', [AdminPolicy::class, 'cargaMasiva']);
        Gate::define('tablero_control', [AdminPolicy::class, 'tableroControl']);
        Gate::define('informes', [AdminPolicy::class, 'informes']);
        Gate::define('admin_panel', [AdminPolicy::class, 'adminPanel']);
        Gate::define('admin_usuarios', [AdminPolicy::class, 'adminUsuarios']);
        Gate::define('admin_clientes', [AdminPolicy::class, 'adminClientes']);
        Gate::define('admin_roles', [AdminPolicy::class, 'adminRoles']);
        Gate::define('admin_areas', [AdminPolicy::class, 'adminAreas']);
        Gate::define('admin_modulos', [AdminPolicy::class, 'adminModulos']);
        Gate::define('admin_tipoincidente', [AdminPolicy::class, 'adminTipoIncidente']);
        Gate::define('admin_tiposervicio', [AdminPolicy::class, 'adminTipoServicio']);
        Gate::define('admin_tipoavance', [AdminPolicy::class, 'adminTipoAvance']);

        Gate::define('ver_inc', [AdminPolicy::class, 'verIncidente']);
    }
}
