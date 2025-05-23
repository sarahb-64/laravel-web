<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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
        
        Gate::define('manage-projects', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('seo-manager');
        });
        
        Gate::define('view-project', function ($user, $project) {
            return $project->user_id === $user->id;
        });

        Gate::policy(Project::class, ProjectPolicy::class);
    }
}
