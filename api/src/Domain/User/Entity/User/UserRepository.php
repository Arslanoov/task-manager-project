<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Exception\User\UserNotFoundException;

final class UserRepository
{
    private EntityManagerInterface $em;
    private ObjectRepository $users;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->users = $em->getRepository(User::class);
    }

    public function findById(Id $id): ?User
    {
        /** @var User $user */
        $user = $this->users->find($id->getValue());
        return $user;
    }

    public function findByLogin(Login $login): ?User
    {
        /** @var User $user */
        $user = $this->users->findOneBy([
            'login' => $login
        ]);

        return $user;
    }

    public function findByEmail(Email $email): ?User
    {
        /** @var User $user */
        $user = $this->users->findOneBy([
            'email' => $email
        ]);

        return $user;
    }

    public function hasByLogin(Login $login): bool
    {
        return boolval($this->findByLogin($login));
    }

    public function hasByEmail(Email $email): bool
    {
        return boolval($this->findByEmail($email));
    }

    public function getById(Id $id): User
    {
        if (!$user = $this->findById($id)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }

    public function findAll(): array
    {
        $users = (array) $this->users->findAll();
        return array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'login' => $user->getLogin(),
                'status' => $user->getStatus()
            ];
        }, $users);
    }
}