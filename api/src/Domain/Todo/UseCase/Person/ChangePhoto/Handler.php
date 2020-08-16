<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\ChangePhoto;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\BackgroundPhoto;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoUploader;

final class Handler
{
    private PersonRepository $persons;
    private PhotoUploader $uploader;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param PhotoUploader $uploader
     * @param FlusherInterface $flusher
     */
    public function __construct(PersonRepository $persons, PhotoUploader $uploader, FlusherInterface $flusher)
    {
        $this->persons = $persons;
        $this->uploader = $uploader;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new Id($command->personId));

        $path = $this->uploader->upload($command->file);
        $person->changeBackgroundPhoto(new BackgroundPhoto($path));

        $this->persons->add($person);

        $this->flusher->flush();
    }
}