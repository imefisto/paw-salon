<?php

declare(strict_types=1);

use PawSalon\Infrastructure\DependencyInjection\ContainerFactory;
use PawSalon\Infrastructure\Swoole\Server;

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/src/config/config.php';
$dependencies = require __DIR__ . '/src/config/dependencies.php';
$routes = require __DIR__ . '/src/config/routes.php';
$container = ContainerFactory::create($config, $dependencies, $routes);

$server = $container->get(Server::class);

$server->set([
    'document_root' => __DIR__ . '/public',
    'enable_static_handler' => true,
    'http_autoindex' => true,
    'http_index_files' => ['index.html'],
]);

$server->run();
