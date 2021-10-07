<?php

declare(strict_types=1);

namespace App\ADR\Command;

use App\ADR\Service\ActionRegistry;
use App\Api\Action\IndexAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListActionsCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:list-actions';

    private ActionRegistry $actionRegistry;
    private IndexAction $indexAction;

    public function __construct(ActionRegistry $actionRegistry, IndexAction $indexAction)
    {
        $this->actionRegistry = $actionRegistry;
        $this->indexAction = $indexAction;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $indexAction = $this->indexAction;

        $actionRows = $this->getActionRows();

        $io->text('Registered Actions:');

        $io->table(['Action', 'Version', 'Reference'], $actionRows);

        $versions = $this->actionRegistry->getVersionList();

        $io->success(sprintf('Registered versions: %s', implode(', ', $versions)));

        $indexActionStatus = $indexAction()['status'];

        $io->text(sprintf("Index action status says: %s", var_export($indexActionStatus, true)));

        return Command::SUCCESS;
    }

    private function getActionRows(): array
    {
        $rows = [];
        foreach ($this->actionRegistry->getActions() as $action => $versions) {
            foreach ($versions as $version => $reference) {
                $rows[] = [$action, $version, get_class($reference)];
            }
        }

        return $rows;
    }

}