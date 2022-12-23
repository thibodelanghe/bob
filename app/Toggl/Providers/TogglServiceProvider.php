<?php

namespace App\Toggl\Providers;

use App\Toggl\Clients\Toggl;
use Illuminate\Support\ServiceProvider;

class TogglServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'toggl');

//        $this->app->bind(Toggl::class, function () {
//            return app(Toggl::class);
//        });
    }
}
