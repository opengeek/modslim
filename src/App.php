<?php
/*
 * This file is part of the MODSlim package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenGeek\MODSlim;


use DI\ContainerBuilder;

class App extends \DI\Bridge\Slim\App
{
    public function applyMiddleware()
    {
        $middleware = new Middleware();
        $middleware($this);
    }

    public function buildRoutes()
    {
        $routes = new Routes();
        $routes($this);
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . '/container.php');
    }
}
