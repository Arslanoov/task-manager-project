<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use Webmozart\Assert\Assert;

final class Type
{
    private const TYPE_MAIN = 'Main';
    private const TYPE_DAILY = 'Daily';

    private string $value;

    /**
     * Type constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 2, 16);
        Assert::oneOf($value, self::types());
        $this->value = $value;
    }

    public static function main(): self
    {
        return new self(self::TYPE_MAIN);
    }

    public static function daily(): self
    {
        return new self(self::TYPE_DAILY);
    }

    public function isMain(): bool
    {
        return $this->value === self::TYPE_MAIN;
    }

    public function isDaily(): bool
    {
        return $this->value === self::TYPE_DAILY;
    }

    public static function types(): array
    {
        return [
            self::TYPE_MAIN,
            self::TYPE_DAILY
        ];
    }
}