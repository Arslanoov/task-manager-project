<?php

declare(strict_types=1);

namespace Domain\User\UseCase\User\SignUp;

final class Command
{
    public string $id;
    public string $login;
    public string $email;
    public string $password;

    /**
     * Command constructor.
     * @param string $id
     * @param string $login
     * @param string $email
     * @param string $password
     */
    public function __construct(string $id, string $login, string $email, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
    }
}