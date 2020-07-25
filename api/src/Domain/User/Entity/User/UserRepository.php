<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Transaction;
use Domain\Exception\User\UserNotFoundException;

final class UserRepository
{
    private ORMInterface $orm;
    private RepositoryInterface $users;
    private Transaction $transaction;

    /**
     * UserRepository constructor.
     * @param ORMInterface $orm
     * @param Transaction $transaction
     */
    public function __construct(ORMInterface $orm, Transaction $transaction)
    {
        $this->orm = $orm;
        $this->users = $orm->getRepository(User::class);
        $this->transaction = $transaction;
    }

    public function findById(UserId $id): ?User
    {
        /** @var User $user */
        $user = $this->users->findByPK($id->getValue());
        return $user;
    }

    public function findByLogin(Login $login): ?User
    {
        /** @var User $user */
        $user = $this->users->findOne([
            'login' => $login->getRaw()
        ]);

        return $user;
    }

    public function findByEmail(Email $email): ?User
    {
        /** @var User $user */
        $user = $this->users->findOne([
            'email' => $email->getValue()
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

    public function getById(UserId $id): User
    {
        if (!$user = $this->findById($id)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->transaction->persist($user);
    }

    public function remove(User $user): void
    {
        $this->transaction->delete($user);
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