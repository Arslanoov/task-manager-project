<?php

use Infrastructure\Domain as Implementation;
use Domain;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        Domain\User\Entity\User\UserRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\User\DoctrineUserRepository::class);
        },

        Domain\Todo\Entity\Person\PersonRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\DoctrinePersonRepository::class);
        },
        Domain\Todo\Entity\Schedule\ScheduleRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\DoctrineScheduleRepository::class);
        },
        Domain\Todo\Entity\Schedule\Task\TaskRepository::class => function (ContainerInterface $container) {
            return $container->get(Domain\Todo\Entity\Schedule\Task\TaskRepository::class);
        },
        Domain\Todo\Entity\Schedule\Task\Step\StepRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\DoctrineStepRepository::class);
        }
    ]
];