<?php

declare(strict_types=1);

use App\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

$capsule = require dirname(__DIR__) . '/config/database.php';

exit((new Application($capsule))->run($argv));
