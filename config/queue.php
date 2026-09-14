<?php
return ['default' => env('QUEUE_CONNECTION', 'database'), 'connections' => ['database' => ['driver' => 'database', 'connection' => env('DB_CONNECTION'), 'table' => 'jobs', 'queue' => 'default', 'retry_after' => 90, 'after_commit' => false], 'sync' => ['driver' => 'sync']], 'failed' => ['driver' => 'database-uuids', 'database' => env('DB_CONNECTION'), 'table' => 'failed_jobs']];
