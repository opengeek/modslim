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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Collection;

class Handler
{
    /** @var \modX  */
    private $modx;
    /** @var Collection  */
    private $config;

    public function __construct(Collection $config, $modx)
    {
        $this->config = $config;
        $this->modx = $modx;
    }

    function __invoke(Request $request, Response $response)
    {
        $this->modx->initialize($this->config->get('modx.context', 'web'));

        $path = ltrim($request->getUri()->getPath(), '/');

        $this->modx->resource = $this->findResource($path, $response);
        if ($this->modx->resource !== null) {
            $this->modx->resourceIdentifier = $this->modx->resource->get('id');
            $this->modx->elementCache = array();
            $resourceOutput = $this->modx->resource->process();
            $this->modx->parser->processElementTags('', $resourceOutput, true, false, '[[', ']]', array(), 10);
            $this->modx->parser->processElementTags('', $resourceOutput, true, true, '[[', ']]', array(), 10);

            $response->getBody()->write($resourceOutput);
        } else {
            $response = $response->withStatus(500, "An error occurred while loading the resource");
        }

        return $response;
    }

    /**
     * @param string   $path
     * @param Response $response
     *
     * @return null|\modResource
     */
    protected function findResource($path, Response &$response)
    {
        if (empty($path)) {
            $resourceId = $this->modx->getOption('site_start', null, 1);
        } else {
            $resourceId = $this->modx->findResource($path);
        }
        if (!$resourceId) {
            $response = $response->withStatus(404);
            $resourceId = (int)$this->modx->getOption('error_page', null,
                $this->modx->getOption('site_start', null, 1));
        }

        return $this->modx->getObject('modResource', $resourceId);
    }
}
