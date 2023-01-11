<?php

namespace App\Dto;

class EntityAttributes
{
    public function __construct(
        public ?int $minMireds = null,
        public ?int $maxMireds = null,
        public array $effectList = [],
        public array $supportedColorModes = [],
        public ?string $colorMode = null,
        public ?int $brightness = null,
        public ?int $colorTemp = null,
        public array $hsColor = [],
        public array $rgbColor = [],
        public array $xyColor = [],
        public array $entityId = [],
        public ?string $icon = null,
        public ?string $friendlyName = null,
        public ?int $supportedFeatures = null,

    )
    {
    }
}