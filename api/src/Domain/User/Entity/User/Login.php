<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Login
 * @package Domain\User\Entity
 * @Cycle\Embeddable()
 */
final class Login
{
    /** @Cycle\Column(type="string(32)", name="login") */
    private string $value;

    /**
     * Login constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 4, 32);
        $this->value = $value;
    }

    public function getRaw(): string
    {
        return $this->value;
    }

    public function isEqual(Login $login): bool
    {
        return $this->value === $login->getRaw();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}