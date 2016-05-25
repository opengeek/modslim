<?php
/*
 * This file is part of the MODSlim package.
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
$app->run();

