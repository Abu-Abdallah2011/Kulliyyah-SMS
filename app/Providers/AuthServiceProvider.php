<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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

        Gate::define('isAdmin', function($user){
            return $user->role == 'ADMIN';
        });

        Gate::define('isExecutive', function($user){
            return $user->role == 'EXECUTIVE' || $user->role == 'ADMIN';
        });

        Gate::define('isTeacher', function($user){
            return $user->role == 'TEACHER' || $user->role == 'EXECUTIVE' || $user->role == 'ADMIN';
        });

        Gate::define('isAssistant', function($user){
            return $user->role == 'ASSISTANT' || $user->role == 'TEACHER' || $user->role == 'EXECUTIVE' || $user->role == 'ADMIN';
        });

        Gate::define('isGuardian', function($user){
            return $user->role == 'GUARDIAN';
        });
        
        Gate::define('isAdGuardian', function($user){
            return $user->role == 'GUARDIAN' || $user->role == 'ADMIN';
        });
    }
}
