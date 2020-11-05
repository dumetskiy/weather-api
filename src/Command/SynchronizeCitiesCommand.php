<?php

declare(strict_types=1);

namespace App\Command;

use App\DataSynchonizer\CitiesDataSynchronizer;
use App\Exception\Console\ConsoleRuntimeException;
use App\OutputManager\Manager\ConsoleOutputManager;
use App\OutputManager\Provider\OutputManagerProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizeCitiesCommand extends Command
{
    private const OPTION_FORCE = 'force';
    private const OPTION_FORCE_SHORTCUT = 'f';
    private const COMMAND_NAME = 'app:synchronize-cities';

    private CitiesDataSynchronizer $citiesDataSynchronizer;

    public function __construct(
        CitiesDataSynchronizer $citiesDataSynchronizer
    ) {
        $this->citiesDataSynchronizer = $citiesDataSynchronizer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Synchronizes local DB cities data with Musement API')
            ->addOption(
                self::OPTION_FORCE,
                self::OPTION_FORCE_SHORTCUT,
                InputOption::VALUE_OPTIONAL,
                'Apply database changes',
                false
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->citiesDataSynchronizer->setOutputManagerProvider(
                new OutputManagerProvider(
                    new ConsoleOutputManager($output)
                )
            );

            $this->citiesDataSynchronizer->synchronizeCities(false !== $input->getOption(self::OPTION_FORCE));

            return self::SUCCESS;
        } catch (ConsoleRuntimeException $exception) {
            return self::FAILURE;
        }
    }
}