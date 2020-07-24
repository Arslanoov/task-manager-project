<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Cycle\Annotated\Annotation as Cycle;

/**
 * @Cycle\Entity(
 *     table="user_users",
 *     role="user"
 * )
 * @Cycle\Table(
 *      indexes={
 *          @Cycle\Table\Index(columns = {"login", "email"})
 *      }
 * )
 */
final class User
{
    /**
     * @Cycle\Column(type="primary", primary=true)
     * @Cycle\Relation\Embedded(target="UserId")
     */
    private UserId $id;
    /**
     * @Cycle\Relation\Embedded(target="Login")
     */
    private Login $login;
    /**
     * @Cycle\Relation\Embedded(target="Email")
     */
    private Email $email;
    /**
     * @Cycle\Relation\Embedded(target="Status")
     */
    private Status $status;

    public function __construct(UserId $id, Login $login, Email $email, Status $status)
    {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->status = $status;
    }

    public static function signUpByEmail(Login $login, Email $email): self
    {
        return new self(
            UserId::uuid4(),
            $login,
            $email,
            Status::draft()
        );
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
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
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
}