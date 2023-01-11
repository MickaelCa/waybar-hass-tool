<?php

namespace App\Tools;

class GenerateBar
{
    public static function generate(int $percentage, int $size = 6, string $plainChar = "█", string $emptyChar = "░") : string {

        $bars = round(($percentage * $size) / 100);
        $bar = '';

        for ($i = 1; $i <= $size; $i++) {
            if ($i <= $bars) {
                $bar .= $plainChar;
            } else {
                $bar .= $emptyChar;
            }
        }

        return $bar;
    }
}