<?php

declare(strict_types=1);

namespace Domain\User\Entity;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Email
 * @package Domain\User\Entity
 * @Cycle\Embeddable()
 */
final class Email
{
    /**
     * @Cycle\Column(type="string(32)", name="email")
     */
    private string $value;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::lengthBetween($value, 5, 32);
        Assert::email($value);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Email $email): bool
    {
        return $this->value === $email->getValue();
    }
}