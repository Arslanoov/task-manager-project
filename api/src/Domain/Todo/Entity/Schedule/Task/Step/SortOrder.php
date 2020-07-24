<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Cycle\Annotated\Annotation as Cycle;
use Webmozart\Assert\Assert;

/**
 * Class SortOrder
 * @Cycle\Embeddable()
 */
final class SortOrder
{
    /** @Cycle\Column(type="integer", name="sort_order", nullable=true) */
    private ?int $value = null;

    /**
     * SortOrder constructor.
     * @param int|null $value
     */
    public function __construct(?int $value = null)
    {
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    public function isEqual(SortOrder $order): bool
    {
        return $this->value === $order->getValue();
    }
}