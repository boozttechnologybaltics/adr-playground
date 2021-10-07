<?php

declare(strict_types=1);

namespace App\ADR\Service;

use App\ADR\Action\ActionInterface;
use App\ADR\Exception\UnsupportedApiVersionException;
use Composer\Semver\Comparator;
use InvalidArgumentException;

class ActionRegistry
{
    private array $actionMap = [];
    private array $versionedActionReferenceMap = [];
    private array $versionList = [];

    public function __construct(array $actionMap, array $versionedActionMap, array $versionList)
    {
        $this->actionMap = $actionMap;
        $this->versionedActionReferenceMap = $versionedActionMap;
        $this->versionList = $versionList;
    }

    /**
     * @return string[]
     */
    public function getVersionList(): array
    {
        return $this->versionList;
    }

    public function getAction(string $name, ?string $version): ActionInterface
    {
        if (null === $version) {
            return $this->actionMap[$name];
        }

        $this->assertVersionSupport($version);

        foreach ($this->versionedActionReferenceMap[$name] as $registeredVersion => $action) {
            if (Comparator::compare($version, '>=', $registeredVersion)) {
                return $action;
            }
        }

        return $this->actionMap[$name];
    }

    /**
     * @return array<ActionInterface>
     */
    public function getActions(): array
    {
        return $this->versionedActionReferenceMap;
    }


    private function getSupportRange(): array
    {
        $supportedVersionList = $this->getVersionList();

        if (0 === count($supportedVersionList)) {
            throw new InvalidArgumentException("Version support list is empty");
        }
        $lowestSupportedVersion = reset($supportedVersionList);
        $highestSupportedVersion = end($supportedVersionList);

        return [$lowestSupportedVersion, $highestSupportedVersion];
    }

    private function assertVersionSupport(string $version): void
    {
        [$lowestSupportedVersion, $highestSupportedVersion] = $this->getSupportRange();

        if (Comparator::compare($version, '>=', $lowestSupportedVersion)
            && Comparator::compare($version, '<=', $highestSupportedVersion)
        ) {
            return;
        }

        throw new UnsupportedApiVersionException($version);
    }
}
