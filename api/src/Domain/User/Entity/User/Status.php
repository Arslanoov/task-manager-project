<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Webmozart\Assert\Assert;

final class Status
{
    private const STATUS_DRAFT = 'Draft';
    private const STATUS_ACTIVE = 'Active';

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

    public function __toString(): string
    {
        return (string) $this->value;
    }

    private static function list(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE
        ];
    }
}