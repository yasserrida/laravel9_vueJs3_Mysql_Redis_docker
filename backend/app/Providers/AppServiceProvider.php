<?php

namespace App\Providers;

use App\Http\Controllers\Api\Auth\AccessTokenController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->environment('local')) {
        }
    }

    public function boot()
    {
        $this->app->bind(AccessTokenController::class, AccessTokenController::class);
    }
}
