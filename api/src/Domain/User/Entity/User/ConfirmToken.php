<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use DateTimeImmutable;
use Domain\Exception\DomainException;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConfirmToken
 * @package Domain\User\Entity\User
 * @ORM\Embeddable()
 */
final class ConfirmToken
{
    /**
     * @var string
     * @ORM\Column(type="string", name="value", nullable=true)
     */
    private string $value;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="expires", nullable=true)
     */
    private DateTimeImmutable $expires;

    /**
     * ConfirmToken constructor.
     * @param string $value
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 16, 64);
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        Assert::notEmpty($expires);
        $this->expires = $expires;
    }

    public static function generate(): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable()
        );
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value) or $this->isExpiredTo($date)) {
            throw new DomainException('Token is invalid.');
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function isEqualTo(string $token): bool
    {
        return $this->value === $token;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}
