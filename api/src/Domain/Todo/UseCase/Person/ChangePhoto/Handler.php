<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\ChangePhoto;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\BackgroundPhoto;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoRemoverInterface;
use Domain\Todo\Service\PhotoUploaderInterface;

final class Handler
{
    private PersonRepository $persons;
    private PhotoUploaderInterface $uploader;
    private PhotoRemoverInterface $remover;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param PhotoUploaderInterface $uploader
     * @param PhotoRemoverInterface $remover
     * @param FlusherInterface $flusher
     */
    public function __construct(
        PersonRepository $persons,
        PhotoUploaderInterface $uploader,
        PhotoRemoverInterface $remover,
        FlusherInterface $flusher
    ) {
        $this->persons = $persons;
        $this->uploader = $uploader;
        $this->remover = $remover;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new Id($command->personId));
        if ($person->hasBackgroundPhoto()) {
            $this->remover->remove($person->getBackgroundPhoto()->getPath());
        }

        $path = $this->uploader->upload($command->file);
        $person->changeBackgroundPhoto(new BackgroundPhoto($path));

        $this->persons->add($person);

        $this->flusher->flush();
    }
}
