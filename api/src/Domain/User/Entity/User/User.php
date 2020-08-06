<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"login"}),
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
final class User
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="user_user_id")
     */
    private Id $id;
    /**
     * @ORM\Column(type="user_user_login")
     */
    private Login $login;
    /**
     * @ORM\Column(type="user_user_email")
     */
    private Email $email;
    /**
     * @ORM\Column(type="user_user_password")
     */
    private Password $password;
    /**
     * @ORM\Column(type="user_user_status")
     */
    private Status $status;

    public function __construct(Id $id, Login $login, Email $email, Password $password, Status $status)
    {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
    }

    public static function signUpByEmail(Login $login, Email $email, Password $password): self
    {
        return new self(
            Id::uuid4(),
            $login,
            $email,
            $password,
            Status::draft()
        );
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

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
}