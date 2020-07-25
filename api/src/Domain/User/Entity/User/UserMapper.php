<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Domain\Mapper\CustomMapper;

/**
 * Class UserMapper
 * @package Domain\User\Entity\User
 * Custom mapper
 */
final class UserMapper extends CustomMapper
{
    public function hydrate($entity, array $data)
    {
        return new User(
            new UserId($data['id']),
            new Login($data['login']),
            new Email($data['email']),
            new Password($data['password']),
            new Status($data['status'])
        );
    }

    /**
     * @param User $entity
     * @return array
     */
    public function extract($entity): array
    {
        return [
            'id' => $entity->getId()->getValue(),
            'login' => $entity->getLogin()->getRaw(),
            'email' => $entity->getEmail()->getValue(),
            'password' => $entity->getPassword()->getValue(),
            'status' => $entity->getStatus()->getValue()
        ];
    }
}