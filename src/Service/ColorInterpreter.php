<?php

namespace App\Service;

use App\Data\Colors;

class ColorInterpreter
{
    public function nameColor(int $r, int $g, int $b): string
    {

        $closest = null;
        $closestValue = 0;

        foreach (Colors::$list as $name => [$r2, $g2, $b2]) {

            $distance = sqrt((($r - $r2) ** 2) + (($g - $g2) ** 2) + (($b - $b2) ** 2));

            if ($closest === null || $distance < $closestValue) {
                $closest = $name;
                $closestValue = $distance;
            }
        }

        return $closest;
    }
}