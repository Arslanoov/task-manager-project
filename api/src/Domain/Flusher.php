<?php

declare(strict_types=1);

namespace Domain;

use Doctrine\ORM\EntityManagerInterface;

final class Flusher implements FlusherInterface
{
    private EntityManagerInterface $em;

    /**
     * Flusher constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}