<?php

namespace App\Dto;

class Entity
{
    public function __construct(
        public ?string $entityId = null,
        public ?string $state = null,
        public ?EntityAttributes $attributes = null,
        public ?\DateTimeImmutable $lastChanged = null,
        public ?\DateTimeImmutable $lastUpdated = null,
        public array $context = [],
    )
    {
    }
}