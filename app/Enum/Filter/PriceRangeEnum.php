<?php

namespace App\Enum\Filter;

enum PriceRangeEnum: int
{
    case LESS_THAN_100 = 1;
    case BETWEEN_100_AND_150 = 2;
    case BETWEEN_150_AND_200 = 3;
    case OVER_200 = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::LESS_THAN_100 => '< 100 €',
            self::BETWEEN_100_AND_150 => '100 a 150 €',
            self::BETWEEN_150_AND_200 => '150 a 200 €',
            self::OVER_200 => '> 200 €',
        };
    }
}
