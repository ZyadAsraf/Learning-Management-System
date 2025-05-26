<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Role;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
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
        Gate::define('view-admin-dashboard', function (User $user) {
       return $user->role && $user->role->name == "admin";
        });
            Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
                Gate::define('view-student-dashboard', function (User $user) {
       return $user->role && $user->role->name == "student";
        });
        
        Gate::define('view-teacher-dashboard', function (User $user) {
       return $user->role && $user->role->name == "teacher";
        });
    }
}
