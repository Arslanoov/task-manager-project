<?php

use App\Service\TransactionInterface;
use App\Service\UuidGeneratorInterface;
use Domain\FlusherInterface;
use Domain\Todo\Service\PhotoRemoverInterface;
use Domain\Todo\Service\PhotoUploaderInterface;
use Domain\User\Service\PasswordHasherInterface;
use Domain\User\Service\PasswordValidatorInterface;
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
        TransactionInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\DoctrineTransaction::class);
        },

        PasswordHasherInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\User\Service\PasswordHasher::class);
        },
        PasswordValidatorInterface::class => function (ContainerInterface $container)  {
            return $container->get(Implementation\Domain\User\Service\PasswordValidator::class);
        },

        PhotoUploaderInterface::class => function (ContainerInterface $container) {
            return new Implementation\Domain\Todo\Service\PhotoUploader(
                $container->get('config')['service']['background_photo_path']
            );
        },
        PhotoRemoverInterface::class => function (ContainerInterface $container) {
            return new Implementation\Domain\Todo\Service\PhotoRemover(
                $container->get('config')['service']['background_photo_path']
            );
        }
    ]
];