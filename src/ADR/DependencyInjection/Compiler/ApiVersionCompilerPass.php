<?php

declare(strict_types=1);

namespace App\ADR\DependencyInjection\Compiler;

use App\ADR\Action\ActionInterface;
use App\ADR\Action\VersionedActionInterface;
use App\ADR\Service\ActionRegistry;
use Composer\Semver\Comparator;
use Composer\Semver\Semver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ApiVersionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ActionRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(ActionRegistry::class);

        $taggedServices = $container->findTaggedServiceIds('app.action');

        $actionMap = [];
        $versionedActionMap = [];
        $versionList = [];

        foreach ($taggedServices as $id => $tags) {
            $reference = new Reference($id);
            $actionDefinition = $container->getDefinition($id);

            /** @var ActionInterface|VersionedActionInterface $action */
            $action = $actionDefinition->getClass();
            $name = $action::getActionName();

            if (!in_array(VersionedActionInterface::class, class_implements($action), true)) {
                $actionMap[$name] = $reference;

                continue;
            }

            $version = $action::getLowestSupportedVersion();

            $versionedActionMap = $this->addVersionedAction($versionedActionMap, $name, $reference, $version);
            $versionList = $this->addVersion($versionList, $version);
        }

        $definition->setBindings(
            [
                '$actionMap' => $actionMap,
                '$versionedActionMap' => $versionedActionMap,
                '$versionList' => $versionList,
            ]
        );
    }


    /**
     * @param string[] $versions
     * @param string   $version
     *
     * @return string[]
     */
    private function addVersion(array $versions, string $version): array
    {
        $versions[] = $version;
        $versions = array_unique($versions);

        return Semver::sort($versions);
    }

    private function addVersionedAction(
        array $versionedActionMap,
        string $name,
        Reference $reference,
        string $version
    ): array {
        $versionedActionMap[$name] = $versionedActionMap[$name] ?? [];
        $versionedActionMap[$name][$version] = $reference;
        $versionedActionMap[$name] = $this->sortVersionedActions($versionedActionMap[$name]);

        return $versionedActionMap;
    }

    private function sortVersionedActions(array $versionedActions): array
    {
        uksort($versionedActions, function (string $a, string $b): int {
            if ($a === $b) {
                return 0;
            }

            if (Comparator::lessThan($a, $b)) {
                return 1;
            }

            return -1;
        });

        return $versionedActions;
    }
}
