<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use App\Models\User;
use App\Models\Role;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // You can map your models to policies here if you create them
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate to allow only admins to view the admin dashboard
        Gate::define('view-admin-dashboard', function (User $user) {
       return $user->role && $user->role->name == "admin";
        });

        Gate::define('view-student-dashboard', function (User $user) {
       return $user->role && $user->role->name == "student";
        });
        
        Gate::define('view-teacher-dashboard', function (User $user) {
       return $user->role && $user->role->name == "teacher";
        });
        // You can define more gates here as needed
    }
}
