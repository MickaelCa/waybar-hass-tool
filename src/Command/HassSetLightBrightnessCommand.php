<?php

namespace App\Command;

use App\Service\HassService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hass:set-light-brightness',
    description: 'Add a short description for your command',
)]
class HassSetLightBrightnessCommand extends Command
{
    public function __construct(
        private readonly HassService $hassService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('lightName', InputArgument::REQUIRED, 'light name')
            ->addArgument('value', InputArgument::REQUIRED, 'light brightness value')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lightName = $input->getArgument('lightName');
        $value = (int) $input->getArgument('value');

        $this->hassService->changeBrightnessByValue($value, $lightName);

        return Command::SUCCESS;
    }
}
