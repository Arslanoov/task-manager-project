<?php

use App\Service\UuidGeneratorInterface;
use Domain\FlusherInterface;
use Infrastructure\Service\DoctrineFlusher;
use Infrastructure\Service\RamseyUuidGenerator;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        FlusherInterface::class => function (ContainerInterface $container) {
            return $container->get(DoctrineFlusher::class);
        },
        UuidGeneratorInterface::class => function (ContainerInterface $container) {
            return $container->get(RamseyUuidGenerator::class);
        }
    ]
];