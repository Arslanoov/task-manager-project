<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\RemovePhoto;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoRemover;

final class Handler
{
    private PersonRepository $persons;
    private PhotoRemover $remover;
    private FlusherInterface $flusher;

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new Id($command->id));

        if ($person->hasBackgroundPhoto()) {
            $this->remover->remove($person->getBackgroundPhoto()->getPath());
        }

        $person->removeBackgroundPhoto();

        $this->flusher->flush();
    }
}