<?php

namespace App\Providers;

use App\Helpers\AccountTypes;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Authorization
        $abilities = AccountTypes::getAbilities();
        foreach ($abilities as $ability) {
            Gate::define($ability, function (User $user) use ($ability) {
                return AccountTypes::hasRole($user->account_type, $ability);
            });
        }
    }
}
