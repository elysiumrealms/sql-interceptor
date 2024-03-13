<?php

namespace Elysiumrealms\SQLInterceptor;

use Illuminate\Support\Facades\DB;

class SQLInterceptor
{
    static protected $loggedQueries = [];

    static function intercept(\Closure $callback): SQLInterceptor
    {
        // Backup the original connection name
        $originalConnection = DB::getDefaultConnection();

        // Switch to the custom logging connection
        DB::setDefaultConnection('logging');

        // Execute the function that generates the queries
        $callback();

        // Get the logged queries from the custom connection
        static::$loggedQueries = DB::connection()->queries;

        // Restore the original database connection
        DB::setDefaultConnection($originalConnection);

        return new static;
    }

    public function queries()
    {
        return static::$loggedQueries;
    }
}
