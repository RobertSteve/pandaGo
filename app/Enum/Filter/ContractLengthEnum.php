<?php

namespace App\Enum\Filter;

enum ContractLengthEnum: int
{
    case MONTHS_12 = 12;
    case MONTHS_24 = 24;
    case MONTHS_36 = 36;
    case MONTHS_48 = 48;
    case MONTHS_60 = 60;
}
