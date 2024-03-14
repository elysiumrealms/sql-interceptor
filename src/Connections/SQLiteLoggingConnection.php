<?php

namespace Elysiumrealms\SQLInterceptor\Connections;

use Illuminate\Database\SQLiteConnection;

class SQLiteLoggingConnection extends SQLiteConnection
{
    public $queries = [];

    public function select($query, $bindings = [], $useReadPdo = true)
    {
        // Log or process the query
        $this->queries[] = compact('query', 'bindings');

        // Optionally, return a dummy value if needed for the method contract
        return [];
    }
}
