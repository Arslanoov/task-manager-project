<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class ImportantLevel
 * @Cycle\Embeddable()
 */
final class ImportantLevel
{
    private const NOT_IMPORTANT_LEVEL = 'Not Important';
    private const IMPORTANT_LEVEL = 'Import';
    private const VERY_IMPORTANT_LEVEL = 'Very Important';

    /** @Cycle\Column(type="string(16)", name="important_level") */
    private string $value;

    /**
     * ImportantLevel constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 4, 16);
        Assert::oneOf($value, self::levels());
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isNotImportant(): bool
    {
        return $this->value === self::NOT_IMPORTANT_LEVEL;
    }

    public function isImportant(): bool
    {
        return $this->value === self::IMPORTANT_LEVEL;
    }

    public function isVeryImportant(): bool
    {
        return $this->value === self::VERY_IMPORTANT_LEVEL;
    }

    public static function notImportant(): self
    {
        return new self(self::NOT_IMPORTANT_LEVEL);
    }

    public static function important(): self
    {
        return new self(self::IMPORTANT_LEVEL);
    }

    public static function veryImportant(): self
    {
        return new self(self::VERY_IMPORTANT_LEVEL);
    }

    public static function levels(): array
    {
        return [
            self::NOT_IMPORTANT_LEVEL,
            self::IMPORTANT_LEVEL,
            self::VERY_IMPORTANT_LEVEL
        ];
    }
}