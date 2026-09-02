<?php

namespace App\Providers;

use App\Listeners\AuditAuthListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale(config('app.locale'));
        Event::subscribe(AuditAuthListener::class);

        // Super-Admin Bypass: Administradores e Superintendentes possuem acesso irrestrito
        Gate::before(function ($user, string $ability) {
            if ($user->is_admin || $user->hasRole('admin') || $user->hasRole('Superintendente')) {
                return true;
            }
            return null; // Prossegue para as permissões do Spatie
        });
    }
}
