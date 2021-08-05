<?php
declare(strict_types=1);

use App\Application\Application;

if (PHP_SAPI === 'cli-server') {
    $_SERVER['SCRIPT_NAME'] = pathinfo(__FILE__, PATHINFO_BASENAME);
}

require __DIR__ . '/../vendor/autoload.php';

Application::run();
