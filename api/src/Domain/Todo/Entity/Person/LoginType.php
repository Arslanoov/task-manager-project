<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class LoginType extends StringType
{
    public const NAME = 'todo_person_login';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Login ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Login($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}