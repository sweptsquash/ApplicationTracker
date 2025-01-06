<?php

namespace App\Enums;

enum SalaryPeriodType: string
{
    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case BiWeekly = 'bi-weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
}
