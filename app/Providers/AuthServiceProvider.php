<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
      ResetPassword::createUrlUsing(function (User $user, string $token) {
        $frontend_path = env('FRONTEND_URL').'/reset-password/?token=';
        return $frontend_path.$token;
      });
    }
}
