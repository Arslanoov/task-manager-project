<?php

use Doctrine\ORM\EntityManagerInterface;
use Domain\Flusher;
use Domain\FlusherInterface;
use Furious\Container\Container;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL;

/** @var Container $container */

$container->set(EntityManagerInterface::class, function (Container $container) {
    $params = $container->get('config')['doctrine'];

    $config = Setup::createAnnotationMetadataConfiguration(
        $params['metadata_dirs'],
        $params['dev_mode'],
        $params['cache_dir'],
        new FilesystemCache(
            $params['cache_dir']
        ),
        false
    );

    foreach ($params['types'] as $type => $class) {
        if (!DBAL\Types\Type::hasType($type)) {
            DBAL\Types\Type::addType($type, $class);
        }
    }

    return EntityManager::create(
        $params['connection'],
        $config
    );
});

$container->set(FlusherInterface::class, function (Container $container) {
    return $container->get(Flusher::class);
});