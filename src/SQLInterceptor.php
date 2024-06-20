<?php

namespace Elysiumrealms\SQLInterceptor;

use Closure;
use Illuminate\Support\Facades\DB;

class SQLInterceptor
{
    /**
     * @var array
     */
    protected $loggedQueries = [];

    /**
     * SQLInterceptor constructor.
     */
    public function __construct($queries)
    {
        $this->loggedQueries = $queries;
    }

    /**
     * Intercept the queries
     *
     * @param Closure $callback
     * @return SQLInterceptor
     */
    static function intercept(Closure $callback): SQLInterceptor
    {
        // Backup the original connection name
        $originalConnection = DB::getDefaultConnection();

        // Switch to the custom logging connection
        DB::setDefaultConnection('logging');

        // Execute the function that generates the queries
        $callback();

        // Get the logged queries from the custom connection
        $loggedQueries = DB::connection()->queries;

        // Restore the original database connection
        DB::setDefaultConnection($originalConnection);

        return new static($loggedQueries);
    }

    /**
     * Get the logged queries
     *
     * @return array
     */
    public function queries(): array
    {
        return $this->loggedQueries;
    }
}
