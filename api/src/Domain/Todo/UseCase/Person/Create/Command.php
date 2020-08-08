<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\Create;

final class Command
{
    public string $id;
    public string $login;

    /**
     * Command constructor.
     * @param string $id
     * @param string $login
     */
    public function __construct(string $id, string $login)
    {
        $this->id = $id;
        $this->login = $login;
    }
}