<?php

namespace App\Enums;

enum SalaryType: string
{
    case UNKNOWN = 'unknown';
    case RANGE = 'range';
    case FIXED = 'fixed';
}
