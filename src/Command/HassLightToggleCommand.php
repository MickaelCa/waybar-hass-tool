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
    name: 'hass:light-toggle',
    description: 'Add a short description for your command',
)]
class HassLightToggleCommand extends Command
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $lightName = $input->getArgument('lightName');

        $this->hassService->lightToggle($lightName);

        return Command::SUCCESS;
    }
}
