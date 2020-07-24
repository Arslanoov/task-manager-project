<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Description
 * @Cycle\Embeddable()
 */
final class Description
{
    /** @Cycle\Column(type="text", name="description") */
    private string $value;

    /**
     * Name constructor.
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

    public function isEqual(Description $description): bool
    {
        return $this->value === $description->getValue();
    }
}