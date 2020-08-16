<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

final class BackgroundPhoto
{
    private string $path;

    /**
     * BackgroundPhoto constructor.
     * @param string $path
     */
    public function __construct(string $path = null)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}