<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Observers\ChangeLogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::observe(ChangeLogObserver::class);
        Role::observe(ChangeLogObserver::class);
        Permission::observe(ChangeLogObserver::class);
    }

    public function register()
    {
        //
    }
}
