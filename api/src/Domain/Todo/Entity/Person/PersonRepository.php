<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\PersonNotFoundException;

final class PersonRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $persons;

    /**
     * PersonRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->persons = $em->getRepository(Person::class);
    }

    public function findById(Id $id): ?Person
    {
        /** @var Person|null $person */
        $person = $this->persons->find($id->getValue());
        return $person;
    }

    public function getById(Id $id): Person
    {
        if (!$person = $this->findById($id)) {
            throw new PersonNotFoundException();
        }

        return $person;
    }

    public function add(Person $person): void
    {
        $this->em->persist($person);
    }

    public function remove(Person $person): void
    {
        $this->em->remove($person);
    }
}