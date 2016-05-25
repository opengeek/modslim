<?php
/*
 * This file is part of the MODSlim package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Interop\Container\ContainerInterface;

use function DI\object;
use function DI\get;

return [
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => true,

    'uri' => function(ContainerInterface $c) {
        return $c->get('request')->getUri();
    },

    'config' => function(ContainerInterface $c) {
        $configuration = [
            'appName' => 'MODSlim'
        ];
        if (is_readable(__DIR__ . '/../config.php')) {
            $configuration = array_replace_recursive($configuration, require __DIR__ . '/../config.php');
        }

        return new \Slim\Collection($configuration);
    },

    'modx' => function(ContainerInterface $c) {
        if (!defined('MODX_CORE_PATH')) {
            define('MODX_CORE_PATH', $c->get('config')->get('modx.core_path'));
        }
        if (!defined('MODX_CONFIG_KEY')) {
            define('MODX_CONFIG_KEY', $c->get('config')->get('modx.config_key', 'config'));
        }

        require MODX_CORE_PATH . 'model/modx/modx.class.php';

        $modx = new \modX('', $c->get('config')->get('modx.config', []));

        return $modx;
    },

    \OpenGeek\MODSlim\Handler::class => object()
        ->constructor(get('config'), get('modx')),

    'notFoundHandler' => object(\OpenGeek\MODSlim\Handler::class)
];
