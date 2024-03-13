<?php

namespace Elysiumrealms\SQLInterceptor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Elysiumrealms\SQLInterceptor\Connections\MySQLLoggingConnection;

class SQLInterceptorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DB::extend('logging', function ($config, $name) {
            $config['name'] = $name;
            // Adjust this based on your actual database connection type
            return new MySQLLoggingConnection($config);
        });

        $this->offerPublishing();
        $this->registerCommands();
    }

    /**
     * Setup the resource publishing groups for SQLInterceptor.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/SQLInterceptorServiceProvider.stub' =>
                app_path('Providers/SQLInterceptorServiceProvider.php'),
            ], 'sql-interceptor-provider');
        }
    }

    /**
     * Register the SQLInterceptor Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }
}
