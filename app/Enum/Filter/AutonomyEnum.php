<?php

namespace App\Enum\Filter;

enum AutonomyEnum: int
{
    case LESS_THAN_70_KM = 1;
    case BETWEEN_70_AND_100_KM = 2;
    case OVER_100_KM = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::LESS_THAN_70_KM => '< 70 km',
            self::BETWEEN_70_AND_100_KM => '70 - 100 km',
            self::OVER_100_KM => '> 100 km',
        };
    }
}
