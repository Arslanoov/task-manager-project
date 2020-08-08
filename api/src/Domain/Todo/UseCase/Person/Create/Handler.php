<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\Create;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Login;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Person\PersonRepository;

final class Handler
{
    private PersonRepository $persons;
    private FlusherInterface $flusher;

    public function handle(Command $command): void
    {
        $person = new Person(
            new Id($command->id),
            new Login($command->login)
        );

        $this->persons->add($person);

        $this->flusher->flush();
    }
}