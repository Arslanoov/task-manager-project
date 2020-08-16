<?php

use App\Http\Action\Profile\GetPhotoAction;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoUploader;
use Framework\Http\Psr7\ResponseFactory;
use Furious\Container\Container;

/** @var Container $container */

$container->set(PhotoUploader::class, function (Container $container) {
    return new PhotoUploader($container->get('config')['service']['background_photo_path']);
});

$container->set(GetPhotoAction::class, function (Container $container) {
    return new GetPhotoAction(
        $container->get('config')['service']['background_photo_url'],
        $container->get(PersonRepository::class),
        $container->get(ResponseFactory::class)
    );
});