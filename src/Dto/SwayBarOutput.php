<?php

namespace App\Dto;

class SwayBarOutput
{
    public function __construct(
        public string $text,
        public string $alt,
        public string $tooltip,
        public string $class,
        public int $percentage,
    )
    {
    }
}