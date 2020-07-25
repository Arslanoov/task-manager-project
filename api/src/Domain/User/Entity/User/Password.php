<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Password
 * @package Domain\User\Entity\User
 * @Cycle\Embeddable()
 */
final class Password
{
    /** @Cycle\Column(type="string(128)", name="password") */
    private string $value;

    /**
     * Password constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Password $password): bool
    {
        return $this->value === $password->getValue();
    }
}