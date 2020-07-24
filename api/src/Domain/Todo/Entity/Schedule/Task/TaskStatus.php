<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Status
 * @Cycle\Embeddable()
 */
final class TaskStatus
{
    private const STATUS_NOT_COMPLETE = 'Not Complete';
    private const STATUS_IN_PROGRESS= 'In Progress';
    private const STATUS_COMPLETE = 'Complete';

    /** @Cycle\Column(type="string(16)", name="status") */
    private string $value;

    /**
     * Status constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 4, 16);
        Assert::oneOf($value, self::statuses());
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public static function notComplete(): self
    {
        return new self(self::STATUS_NOT_COMPLETE);
    }

    public static function inProgress(): self
    {
        return new self(self::STATUS_IN_PROGRESS);
    }

    public static function complete(): self
    {
        return new self(self::STATUS_COMPLETE);
    }

    public function isNotComplete(): bool
    {
        return $this->value === self::STATUS_NOT_COMPLETE;
    }

    public function isInProgress(): bool
    {
        return $this->value === self::STATUS_IN_PROGRESS;
    }

    public function isComplete(): bool
    {
        return $this->value === self::STATUS_COMPLETE;
    }

    private static function statuses(): array
    {
        return [
            self::STATUS_NOT_COMPLETE,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETE
        ];
    }
}