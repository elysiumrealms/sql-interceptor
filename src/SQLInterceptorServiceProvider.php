<?php

namespace Elysiumrealms\SQLInterceptor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Elysiumrealms\SQLInterceptor\Connections\MySQLLoggingConnection;
use Elysiumrealms\SQLInterceptor\Connections\PostgresLoggingConnection;
use Elysiumrealms\SQLInterceptor\Connections\SQLiteLoggingConnection;
use Elysiumrealms\SQLInterceptor\Connections\SQLServerLoggingConnection;
use InvalidArgumentException;

class SQLInterceptorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DB::extend('logging', function ($config, $name) {
            $config['name'] = $name;

            switch ($config['driver']) {
                case 'mysql':
                    return new MySQLLoggingConnection($config);
                case 'pgsql':
                    return new PostgresLoggingConnection($config);
                case 'sqlite':
                    return new SQLiteLoggingConnection($config);
                case 'sqlsrv':
                    return new SQLServerLoggingConnection($config);
            }

            throw new InvalidArgumentException(
                "Unsupported driver [{$config['driver']}]"
            );
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
