<?php
/*
 * This file is part of the proto package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = new \OpenGeek\MODSlim\App();
$app->applyMiddleware();
$app->buildRoutes();

// Create the kernel for the FastCGIDaemon library (from the Slim app)
$kernel = new \PHPFastCGI\Adapter\Slim\AppWrapper($app);

// Create the symfony console application
$consoleApplication = (new \PHPFastCGI\FastCGIDaemon\ApplicationFactory())->createApplication($kernel);

// Run the symfony console application
$consoleApplication->run();
