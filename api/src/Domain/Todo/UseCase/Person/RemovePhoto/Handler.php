<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\RemovePhoto;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoRemover;

final class Handler
{
    private PersonRepository $persons;
    private PhotoRemover $remover;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param PhotoRemover $remover
     */
    public function __construct(PersonRepository $persons, PhotoRemover $remover)
    {
        $this->persons = $persons;
        $this->remover = $remover;
    }

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new Id($command->id));

        if ($person->hasBackgroundPhoto()) {
            $this->remover->remove($person->getBackgroundPhoto()->getPath());
        }
    }
}