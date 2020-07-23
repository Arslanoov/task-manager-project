<?php

declare(strict_types=1);

namespace Domain\User\Entity;

use Cycle\Annotated\Annotation as Cycle;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * Class Id
 * @package Domain\User\Entity
 * @Cycle\Embeddable()
 */
final class Id
{
    /** @Cycle\Column(type="string(32)", name="id") */
    private string $value;

    /**
     * Id constructor.
     * @param string $value
     */
    private function __construct(string $value)
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

    public static function uuid4(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function isEqual(Id $id): bool
    {
        return $this->value === $id->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}