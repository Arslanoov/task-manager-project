<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Password;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\Login;
use Domain\User\Entity\User\User;

final class UserBuilder
{
    private Id $id;
    private Login $login;
    private Email $email;
    private Password $password;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->login = new Login('User login');
        $this->email = new Email('app@test.app');
        $this->password = new Password('Password');
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withLogin(Login $login): self
    {
        $builder = clone $this;
        $builder->login = $login;
        return $builder;
    }

    public function withEmail(Email $email): self
    {
        $builder = clone $this;
        $builder->email = $email;
        return $builder;
    }

    public function withPassword(Password $password): self
    {
        $builder = clone $this;
        $builder->password = $password;
        return $builder;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->login,
            $this->email,
            $this->password
        );
    }
}