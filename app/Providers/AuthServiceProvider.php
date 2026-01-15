<?php

namespace App\Providers;

use App\Models\Arsip;
use App\Policies\ArsipPolicy;
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
        Arsip::class => ArsipPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk akses halaman arsip
        Gate::define('access-arsip', function ($user) {
            // Super Admin tidak boleh akses halaman list
            if ($user->role->name === 'superadmin') {
                return false;
            }

            // Semua role lain boleh
            return in_array($user->role->name, [
                'admin_univ',
                'admin_fakultas',
                'admin_prodi',
                'asesor'
            ]);
        });
    }
}
