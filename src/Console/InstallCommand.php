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
    protected $signature = 'db:interceptor:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install SQLInterceptor connection';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Registering SQLInterceptor database connection...');

        $this->registerDatabaseConnection();

        $this->info('SQLInterceptor scaffolding installed successfully.');
    }

    /**
     * Register the database connection in the application configuration file.
     *
     * @return void
     */
    protected function registerDatabaseConnection()
    {
        $databaseConfig = file_get_contents(config_path('database.php'));

        if (Str::contains($databaseConfig, "'logging' => [" . PHP_EOL)) {
            return;
        }

        file_put_contents(config_path('database.php'), str_replace(
            "    'connections' => [" . PHP_EOL,
            "    'connections' => [" . PHP_EOL  . PHP_EOL .
                "        'logging' => [" . PHP_EOL .
                "            'driver' => 'mysql'" . PHP_EOL .
                "        ]," . PHP_EOL,
            $databaseConfig
        ));
    }
}
