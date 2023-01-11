<?php

namespace App\Command;

use App\Dto\SwayBarOutput;
use App\Service\ColorInterpreter;
use App\Service\HassService;
use App\Tools\GenerateBar;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hass:get-light-state',
    description: 'Add a short description for your command',
)]
class HassGetLightStateCommand extends Command
{

    public function __construct(
        private readonly HassService $hassService,
        private readonly ColorInterpreter $colorInterpreter,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('lightName', InputArgument::REQUIRED, 'Light entity name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $lightName = $input->getArgument('lightName');

        $lightEntity = $this->hassService->getLightState($lightName);

        $luminosity = (int) round($lightEntity->attributes->brightness / 255 * 100);
        $colorName = $luminosity > 0
            ? $this->colorInterpreter->nameColor(...$lightEntity->attributes->rgbColor)
            : '';

        $output = new SwayBarOutput(
            text: GenerateBar::generate($luminosity, 10),
            alt: '',
            tooltip: $colorName,
            class: '',
            percentage: $luminosity
        );

        echo json_encode($output, JSON_THROW_ON_ERROR);

        return Command::SUCCESS;
    }
}
