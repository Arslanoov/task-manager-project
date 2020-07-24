<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\User\Entity\Email;
use Domain\User\Entity\UserId;
use Domain\User\Entity\Login;
use Domain\User\Entity\Status;
use Domain\User\Entity\User;

final class UserBuilder
{
    private UserId $id;
    private Login $login;
    private Email $email;
    private Status $status;

    public function __construct()
    {
        $this->id = UserId::uuid4();
        $this->login = new Login('User login');
        $this->email = new Email('app@test.app');
        $this->status = Status::draft();
    }

    public function withId(UserId $id): self
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

    public function withStatus(Status $status): self
    {
        $builder = clone $this;
        $builder->status = $status;
        return $builder;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->login,
            $this->email,
            $this->status
        );
    }
}