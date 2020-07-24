<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Cycle\Annotated\Annotation as Cycle;

/**
 * Class TaskId
 * @Cycle\Embeddable()
 */
final class TaskId
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

    public function isEqual(TaskId $id): bool
    {
        return $this->value === $id->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}