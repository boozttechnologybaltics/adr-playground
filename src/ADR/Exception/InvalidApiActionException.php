<?php

declare(strict_types=1);

namespace App\ADR\Exception;

use RuntimeException;

final class InvalidApiActionException extends RuntimeException
{
    public function __construct(string $action)
    {
        parent::__construct(sprintf("Invalid API Action requested: `%s`", $action));
    }

}