<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Name
 * @Cycle\Embeddable()
 */
final class Name
{
    /** @Cycle\Column(type="string(32)", name="name") */
    private string $value;

    /**
     * Name constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 4, 32);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Name $name): bool
    {
        return $this->value === $name->getValue();
    }
}