<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Webmozart\Assert\Assert;

class Id
{
    private int $value;

    public function __construct(int $value)
    {
        Assert::notEmpty($value);
        Assert::integer($value);
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}