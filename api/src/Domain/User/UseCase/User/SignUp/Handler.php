<?php

declare(strict_types=1);

namespace Domain\User\UseCase\User\SignUp;

use Domain\Exception\User\UserAlreadyExistsException;
use Domain\FlusherInterface;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\Login;
use Domain\User\Entity\User\Password;
use Domain\User\Entity\User\User;
use Domain\User\Entity\User\UserRepository;
use Domain\User\Service\PasswordHasherInterface;

final class Handler
{
    private UserRepository $users;
    private PasswordHasherInterface $hasher;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param PasswordHasherInterface $hasher
     * @param FlusherInterface $flusher
     */
    public function __construct(UserRepository $users, PasswordHasherInterface $hasher, FlusherInterface $flusher)
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
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
            new Id($command->id),
            $login,
            $email,
            new Password($this->hasher->hash($command->password))
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}