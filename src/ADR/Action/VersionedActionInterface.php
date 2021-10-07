<?php

declare(strict_types=1);

namespace App\ADR\Action;

interface VersionedActionInterface
{
    /**
     * Returns the lowest supported version number which is covered by this action
     *
     * @return string|null
     */
    public static function getLowestSupportedVersion(): ?string;
}