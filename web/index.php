<?php

use \IngoWalther\ImageMinifyApi\DependencyInjection\ContainerBuilder;
use \IngoWalther\ImageMinifyApi\Validator\RequestValidator;
use \Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$containerBuilder = new ContainerBuilder();
/** @var Symfony\Component\DependencyInjection\Container $container */
$container = $containerBuilder->build(realpath(__DIR__ . '/../'));


$app->register(new Predis\Silex\ClientServiceProvider(), [
    'predis.parameters' => $container->getParameter('redis_config.host'),
    'predis.options'    => [
        'prefix'  => $container->getParameter('redis_config.prefix'),
        'profile' => $container->getParameter('redis_config.profile'),
    ],
]);

$app->post('/minify', function (Request $request) use ($container) {

    $validator = new RequestValidator();
    $validator->validateRequest($request);

    /** @var \IngoWalther\ImageMinifyApi\Security\ApiKeyCheck $apiKeyCheck */
    $apiKeyCheck = $container->get('apiKeyCheck');
    $user = $apiKeyCheck->check($request->request->get('api_key'));

    /** @var \IngoWalther\ImageMinifyApi\Minify\Minify $minify */
    $minify = $container->get('minify');

    return $minify->minify($request->files->get('image'), $user);
});

$app->error(function (\Exception $e, $code) use ($container) {
    $errorHandler = $container->get('errorHandler');
    return $errorHandler->handle($e, $code);
});

$app->run();
