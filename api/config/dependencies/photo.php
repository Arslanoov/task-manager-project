<?php

use App\Http\Action\Profile\GetPhotoAction;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoRemover;
use Domain\Todo\Service\PhotoUploader;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        PhotoUploader::class => function (ContainerInterface $container) {
            return new PhotoUploader($container->get('config')['service']['background_photo_path']);
        },
        PhotoRemover::class => function (ContainerInterface $container) {
            return new PhotoRemover($container->get('config')['service']['background_photo_path']);
        },
        GetPhotoAction::class => function (ContainerInterface $container) {
            return new GetPhotoAction(
                $container->get('config')['service']['background_photo_url'],
                $container->get(PersonRepository::class),
                $container->get(ResponseFactory::class)
            );
        }
    ]
];