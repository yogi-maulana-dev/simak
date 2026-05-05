<?php

namespace App\Providers;

use App\Models\File;
use App\Models\Folder;
use App\Models\SharedLink;
use App\Observers\FileObserver;
use App\Observers\FolderObserver;
use App\Observers\SharedLinkObserver;
use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Gate untuk halaman super_admin
        Gate::define('super_admin', fn ($user) => $user->isSuperAdmin());

        // ── Auth event listeners ──
        Event::listen(Login::class, function (Login $event) {
            ActivityLogger::log(
                action: 'login',
                description: "User {$event->user->name} berhasil login",
                user: $event->user,
            );
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                ActivityLogger::log(
                    action: 'logout',
                    description: "User {$event->user->name} logout",
                    user: $event->user,
                );
            }
        });

        Event::listen(Failed::class, function (Failed $event) {
            $email = $event->credentials['email'] ?? '(unknown)';
            ActivityLogger::log(
                action: 'login_failed',
                description: "Percobaan login gagal untuk email: {$email}",
                metadata: ['email' => $email],
            );
        });

        Event::listen(Lockout::class, function () {
            ActivityLogger::log(
                action: 'login_blocked',
                description: 'Login diblokir sementara karena terlalu banyak percobaan gagal',
            );
        });

        // ── Model observers ──
        File::observe(FileObserver::class);
        Folder::observe(FolderObserver::class);
        SharedLink::observe(SharedLinkObserver::class);
    }
}
