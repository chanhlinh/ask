<?php
return ['default' => env('CACHE_STORE', 'database'), 'stores' => ['database' => ['driver' => 'database', 'connection' => env('DB_CONNECTION'), 'table' => 'cache', 'lock_connection' => null, 'lock_table' => null], 'array' => ['driver' => 'array', 'serialize' => false]], 'prefix' => 'ask_cache_'];
