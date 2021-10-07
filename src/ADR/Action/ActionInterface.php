<?php

declare(strict_types=1);

namespace App\ADR\Action;

interface ActionInterface
{
    /**
     * Returns action name as used in defaults: _action within route definition
     *
     * @return string
     */
    public static function getActionName(): string;
}
