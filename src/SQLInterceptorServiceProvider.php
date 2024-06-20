<?php

namespace Elysiumrealms\SQLInterceptor;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class SQLInterceptorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DB::extend('logging', function ($config, $name) {
            $config['name'] = $name;

            switch ($config['driver']) {
                case 'mysql':
                    return new Connections\MySQLLoggingConnection($config);
                case 'pgsql':
                    return new Connections\PostgresLoggingConnection($config);
                case 'sqlite':
                    return new Connections\SQLiteLoggingConnection($config);
                case 'sqlsrv':
                    return new Connections\SQLServerLoggingConnection($config);
            }

            throw new InvalidArgumentException(
                "Unsupported driver [{$config['driver']}]"
            );
        });

        $this->registerCommands();
        $this->registerServiceProvider();
    }

    /**
     * Register the Service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerServiceProvider()
    {
        if (!$this->app->runningInConsole())
            return;

        $appConfig = file_get_contents(config_path('app.php'));

        $class = static::class . '::class';

        if (Str::contains($appConfig, $class))
            return;

        file_put_contents(config_path('app.php'), str_replace(
            "        /*" . PHP_EOL .
                "         * Package Service Providers..." . PHP_EOL .
                "         */",
            "        /*" . PHP_EOL .
                "         * Package Service Providers..." . PHP_EOL .
                "         */" . PHP_EOL .
                "        " . $class . ",",
            $appConfig
        ));
    }

    /**
     * Register the SQLInterceptor Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole())
            return;

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
