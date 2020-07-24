<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class Status
 * @package Domain\User\Entity
 * @Cycle\Embeddable()
 */
final class Status
{
    private const STATUS_DRAFT = 'Draft';
    private const STATUS_ACTIVE = 'Active';

    /** @Cycle\Column(type="string(16)", name="status") */
    private string $value;

    /**
     * Status constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::lengthBetween($value, 2, 16);
        Assert::oneOf($value, self::list());
        $this->value = $value;
    }

    public static function draft(): self
    {
        return new self(self::STATUS_DRAFT);
    }

    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isDraft(): bool
    {
        return $this->value === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
    }

    public function isEqual(Status $status): bool
    {
        return $this->value === $status->getValue();
    }

    private static function list(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE
        ];
    }
}