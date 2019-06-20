<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Module;
use App\Action;
use App\Company;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Company' => 'App\Policies\CompanyPolicy',
        // Company::class => CompanyPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Dynamically register permissions with Laravel's Gate.
        foreach ($this->getModules() as $module) {
            foreach($this->getActions() as $action) {
                Gate::define($action->action_name.'_'.$module->module_name, function ($user) use ($module, $action) {
                    return $user->hasPermission($module, $action);
                });
            }
        }

    }

    public function getModules()
    {
        return Module::all();
    }

    public function getActions()
    {
        return Action::all();
    }
}
