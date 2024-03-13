<?php

namespace Elysiumrealms\SQLInterceptor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql-interceptor:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the SQLInterceptor resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing SQLInterceptor Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'sql-interceptor-provider']);

        $this->registerSQLInterceptorServiceProvider();

        $this->info('SQLInterceptor scaffolding installed successfully.');
    }

    /**
     * Register the SQLInterceptor service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerSQLInterceptorServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace . '\\Providers\\SQLInterceptorServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class," . PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class," . PHP_EOL . "        {$namespace}\Providers\SQLInterceptorServiceProvider::class," . PHP_EOL,
            $appConfig
        ));

        file_put_contents(config_path('database.php'), str_replace(
            "    'connections' => [" . PHP_EOL,
            "    'connections' => [" . PHP_EOL  . PHP_EOL .
                "        'logging' => [" . PHP_EOL .
                "            'driver' => 'logging'" . PHP_EOL .
                "        ]," . PHP_EOL,
            file_get_contents(config_path('database.php'))
        ));
    }
}
