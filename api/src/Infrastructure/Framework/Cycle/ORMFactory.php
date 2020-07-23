<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Cycle;

use Domain\User\Entity\UserRepository;
use Psr\Container\ContainerInterface;
use Domain\User\Entity\User;
use Cycle\ORM;
use Spiral\Database;

final class ORMFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $orm = new ORM\ORM(new ORM\Factory($container->get(Database\DatabaseManager::class)));

        $schemas = $container->get('config')['schemas'];
        foreach ($schemas as $schema) {
            $orm = $orm->withSchema(new ORM\Schema($schema));
        }

        return $orm;
    }
}