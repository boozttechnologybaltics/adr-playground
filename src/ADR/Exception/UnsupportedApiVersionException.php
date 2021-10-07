<?php

declare(strict_types=1);

namespace App\ADR\Exception;

use RuntimeException;

class UnsupportedApiVersionException extends RuntimeException
{
    public function __construct(string $version)
    {
        parent::__construct(sprintf("Unsupported API version: %s", $version));
    }
}