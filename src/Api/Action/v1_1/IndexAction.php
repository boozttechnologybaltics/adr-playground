<?php

declare(strict_types=1);

namespace App\Api\Action\v1_1;

use App\ADR\Action\ActionInterface;
use App\ADR\Action\VersionedActionInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexAction implements ActionInterface, VersionedActionInterface
{
    public const STATUS_OK = 3;

    public static function getLowestSupportedVersion(): ?string
    {
        return '1.1';
    }

    public static function getActionName(): string
    {
        return 'api.index';
    }

    /**
     * @OA\Get(path="/api/1.1",
     *   operationId="api.index",
     *   tags={"API"},
     *   description="Index Action for the API Demo",
     *   @OA\Response(
     *     response="200",
     *     description="Simple status reporter",
     *     @OA\JsonContent(@OA\Property(property="status", type="integer"))
     *   )
     * )
     */
    public function __invoke(): array
    {
        return ['status' => self::STATUS_OK];
    }
}