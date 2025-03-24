<?php

use PawSalon\Domain\Repository\PetRepository;
use PawSalon\Domain\Service\IdGenerator;
use PawSalon\Infrastructure\Persistence\PDOPetRepository;
use PawSalon\Infrastructure\Routing\Router;
use PawSalon\Infrastructure\Service\UuidGenerator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use function DI\autowire;
use function DI\env;
use function DI\get;

return [
    'db.host' => env('DB_HOST', 'localhost'),
    'db.port' => env('DB_PORT', 3306),
    'db.name' => env('DB_NAME', 'paw_salon'),
    'db.user' => env('DB_USER', 'root'),
    'db.password' => env('DB_PASSWORD', ''),
    'db.charset' => env('DB_CHARSET', 'utf8mb4'),
    'db.config' => function($container) {
        return [
            'host' => $container->get('db.host'),
            'port' => $container->get('db.port'),
            'database' => $container->get('db.name'),
            'user' => $container->get('db.user'),
            'password' => $container->get('db.password'),
            'charset' => $container->get('db.charset'),
        ];
    },
    PetRepository::class => autowire(PDOPetRepository::class)
        ->constructor(config: get('db.config')),
    IdGenerator::class => autowire(UuidGenerator::class),
    ResponseFactoryInterface::class => autowire(Psr17Factory::class),
    Router::class => fn (ContainerInterface $c) => new Router(container: $c, routes: $c->get('routes')),
    StreamFactoryInterface::class => autowire(Psr17Factory::class),
    UriFactoryInterface::class => autowire(Psr17Factory::class),
    UploadedFileFactoryInterface::class => autowire(Psr17Factory::class),
];
