<?php

declare(strict_types=1);

namespace App\OutputManager\Manager;

use App\Exception\Console\ConsoleRuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutputManager implements OutputManagerInterface
{
    private const STYLED_CONSOLE_MESSAGE_TEMPLATE = '<%s>%s</%s>';

    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @throws ConsoleRuntimeException
     */
    public function outputError(string $payload, ?\Exception $exception = null): void
    {
        $this->output->writeln($this->getStyledConsoleMessage($payload, 'error'));

        if ($exception) {
            throw new ConsoleRuntimeException();
        }
    }

    public function outputSuccess(string $payload): void
    {
        $this->output->writeln($this->getStyledConsoleMessage($payload, 'info'));
    }

    public function outputInformation(string $payload): void
    {
        $this->output->writeln($this->getStyledConsoleMessage($payload, 'question'));
    }

    public function outputWarning(string $payload): void
    {
        $this->output->writeln($this->getStyledConsoleMessage($payload, 'comment'));
    }

    private function getStyledConsoleMessage(string $message, string $messageType): string
    {
        return sprintf(self::STYLED_CONSOLE_MESSAGE_TEMPLATE, $messageType, $message, $messageType);
    }
}
