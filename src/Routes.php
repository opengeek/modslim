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


class Routes
{
    public function __invoke(App $app)
    {
        $app->get('{params:.*}', \OpenGeek\MODSlim\Handler::class);
    }
}
