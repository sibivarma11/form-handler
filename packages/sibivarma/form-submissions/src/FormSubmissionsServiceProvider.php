<?php

namespace SibiVarma\FormSubmissions;

use Illuminate\Support\ServiceProvider;

class FormSubmissionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'form-submissions');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'form-submissions-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/form-submissions'),
            ], 'form-submissions-views');

            $this->publishes([
                __DIR__.'/../config/form-submissions.php' => config_path('form-submissions.php'),
            ], 'form-submissions-config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/form-submissions.php', 'form-submissions');
    }
}