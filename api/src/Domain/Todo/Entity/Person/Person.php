<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_persons", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"login"})
 * })
 * @ORM\Entity()
 */
final class Person
{
    /**
     * @var Id
     * @ORM\Column(type="todo_person_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Login
     * @ORM\Column(type="todo_person_login")
     */
    private Login $login;

    /**
     * Person constructor.
     * @param Id $id
     * @param Login $login
     */
    public function __construct(Id $id, Login $login)
    {
        $this->id = $id;
        $this->login = $login;
    }

    public static function new(Login $login): self
    {
        return new self(Id::uuid4(), $login);
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Login
     */
    public function getLogin(): Login
    {
        return $this->login;
    }
}