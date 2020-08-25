<?php

use App\Service\UuidGeneratorInterface;
use Domain\FlusherInterface;
use Domain\Todo\Service\PhotoRemoverInterface;
use Domain\Todo\Service\PhotoUploaderInterface;
use Domain\User\Service\User\PasswordHasherInterface;
use Domain\User\Service\User\PasswordValidatorInterface;
use Infrastructure as Implementation;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        FlusherInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\DoctrineFlusher::class);
        },
        UuidGeneratorInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\RamseyUuidGenerator::class);
        },

        PasswordHasherInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\User\Service\PasswordHasher::class);
        },
        PasswordValidatorInterface::class => function (ContainerInterface $container)  {
            return $container->get(Implementation\Domain\User\Service\PasswordValidator::class);
        },

        PhotoUploaderInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\Todo\Service\PhotoUploader::class);
        },
        PhotoRemoverInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\Todo\Service\PhotoRemover::class);
        }
    ]
];