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
    name: 'hass:shift-light',
    description: 'Add a short description for your command',
)]
class HassShiftLightCommand extends Command
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
            ->addArgument('direction', InputArgument::REQUIRED, 'inc or dec')
            ->addArgument('value', InputArgument::REQUIRED, 'percentage')
            ->addArgument('lightName', InputArgument::REQUIRED, 'light name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $direction = $input->getArgument('direction');
        $value = $input->getArgument('value');
        $lightName = $input->getArgument('lightName');

        if ($direction !== 'inc' && $direction !== 'dec') {
            $io->error('Wrong operation ' . $direction);
            return Command::FAILURE;
        }

        if ($direction === 'dec') {
            $value *= -1;
        }

        $this->hassService->changeBrightnessByStep($value, $lightName);


        return Command::SUCCESS;
    }
}
