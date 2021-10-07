<?php

declare(strict_types=1);

namespace App\ADR\Resolver;

use App\ADR\Exception\InvalidApiActionException;
use App\ADR\Service\ActionRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ActionResolver implements ControllerResolverInterface
{
    private ControllerResolver $resolver;
    private ActionRegistry $actionRegistry;


    public function __construct(
        ControllerResolver $resolver,
        ActionRegistry $actionRegistry
    ) {
        $this->resolver = $resolver;
        $this->actionRegistry = $actionRegistry;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidApiActionException
     */
    public function getController(Request $request)
    {
        $actionName = $request->attributes->get('_action');
        $version = $request->attributes->get('version');

        if (!is_null($actionName)) {
            $action = $this->actionRegistry->getAction($actionName, $version);

            $request->attributes->set('_controller', $action);
        }

        return $this->resolver->getController($request);
    }
}
