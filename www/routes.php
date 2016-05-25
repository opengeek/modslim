<?php
$app->notFound(function() use ($app) {
    /** @var \modX $modx */
    $modx = $app->container->get('modx');
    $modx->initialize($app->config('modx.context') ?: 'web');

    $_GET[$modx->getOption('request_param_alias', null, 'q')]
        = $_REQUEST[$modx->getOption('request_param_alias', null, 'q')]
        = ltrim($app->request->getResourceUri(), '/');

    $modx->handleRequest();
});

$app->get('/', function() use ($app) {
    /** @var \modX $modx */
    $modx = $app->container->get('modx');
    $modx->initialize($app->config('modx.context') ?: 'web');

    $modx->resource = $modx->getObject('modResource', (int)$modx->getOption('site_start'));
    $modx->resourceIdentifier = $modx->resource->get('id');
    $modx->elementCache = array();
    $resourceOutput = $modx->resource->process();
    $modx->parser->processElementTags('', $resourceOutput, true, false, '[[', ']]', array(), 10);
    $modx->parser->processElementTags('', $resourceOutput, true, true, '[[', ']]', array(), 10);

    echo $resourceOutput;
});
