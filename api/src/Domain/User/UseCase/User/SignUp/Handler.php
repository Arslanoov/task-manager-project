<?php

declare(strict_types=1);

namespace Domain\User\UseCase\User\SignUp;

use Domain\Exception\User\UserAlreadyExistsException;
use Domain\TransactionRunnerInterface;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Login;
use Domain\User\Entity\User\Password;
use Domain\User\Entity\User\User;
use Domain\User\Entity\User\UserRepository;
use Domain\User\Service\User\PasswordHasher;

final class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;
    private TransactionRunnerInterface $transaction;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param PasswordHasher $hasher
     * @param TransactionRunnerInterface $transaction
     */
    public function __construct(UserRepository $users, PasswordHasher $hasher, TransactionRunnerInterface $transaction)
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->transaction = $transaction;
    }

    public function handle(Command $command): void
    {
        if ($this->users->hasByLogin($login = new Login($command->login))) {
            throw new UserAlreadyExistsException('User with this login already exists.');
        }
        if ($this->users->hasByEmail($email = new Email($command->email))) {
            throw new UserAlreadyExistsException('User with this email already exists.');
        }

        $user = User::signUpByEmail(
            $login,
            $email,
            new Password($this->hasher->hash($command->password))
        );

        $this->users->add($user);

        $this->transaction->run();
    }
}