<?php

declare(strict_types=1);

namespace Domain\Todo\Service;

use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

final class PhotoRemover
{
    private string $path;

    /**
     * PhotoRemover constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function remove(string $name): void
    {
        if (file_exists($this->path . '/' . $name)) {
            unlink($this->path . '/' . $name);
        }
    }
}