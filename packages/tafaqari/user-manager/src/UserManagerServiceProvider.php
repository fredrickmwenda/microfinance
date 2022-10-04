<?php

namespace Tafaqari\UserManager;

use Illuminate\Support\ServiceProvider;

class UserManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'user-manager');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'user-manager');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('user-manager.php'),
            ], 'user-manager-config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/user-manager'),
            ], 'user-manager-views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/user-manager'),
            ], 'assets');*/

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/user-manager'),
            ], 'user-manager-lang');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'user-manager');

        // Register the main class to use with the facade
        $this->app->singleton('user-manager', function () {
            return new UserManager;
        });

        //Register Controllers
        $this->app->make('Tafaqari\UserManager\Controllers\UserController');
        $this->app->make('Tafaqari\UserManager\Controllers\PermissionController');
        $this->app->make('Tafaqari\UserManager\Controllers\RoleController');
    }
}
