<?php

declare(strict_types=1);

namespace App\Console\Commands\Cycle;

use Exception;
use Spiral\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class RollbackLatestMigrationCommand extends Command
{
    private Migrator $migrator;

    /**
     * RunMigrationsCommand constructor.
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();
        $this->migrator = $migrator;
    }

    protected function configure(): void
    {
        $this
            ->setName('cycle:rollback:latest')
            ->setDescription('Rollback latest migration')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->migrator->rollback();
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}