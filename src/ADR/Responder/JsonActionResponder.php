<?php

declare(strict_types=1);

namespace App\ADR\Responder;

use App\ADR\Action\ActionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class JsonActionResponder
{
    public function __invoke(ViewEvent $viewEvent): void
    {
        $request = $viewEvent->getRequest();

        $controller = $request->attributes->get('_controller');

        if (!$controller instanceof ActionInterface) {
            return;
        }

        $viewEvent->setResponse(
            new JsonResponse(
                json_encode($viewEvent->getControllerResult()),
                Response::HTTP_OK,
                [],
                true
            )
        );
    }
}