<?php

declare(strict_types=1);

namespace App\Api\Action\v1_0;

use App\ADR\Action\ActionInterface;
use App\ADR\Action\VersionedActionInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexAction implements ActionInterface, VersionedActionInterface
{
    public static function getLowestSupportedVersion(): ?string
    {
        return '1.0';
    }

    public static function getActionName(): string
    {
        return 'api.index';
    }

    /**
     * @OA\Get(path="/api/1.0",
     *   operationId="api.index",
     *   tags={"API"},
     *   description="Index Action for the API Demo",
     *   @OA\Response(
     *     response="200",
     *     description="Simple status reporter",
     *     @OA\JsonContent(@OA\Property(property="message", type="string"))
     *   )
     * )
     */
    public function __invoke(): array
    {
        return ['status' => 'OK'];
    }
}