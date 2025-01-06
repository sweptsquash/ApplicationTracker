<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case INTERVIEWING = 'interviewing';
    case OFFER = 'offer';
    case REJECTED = 'rejected';
    case HIRED = 'hired';
    case WITHDRAWN = 'withdrawn';
}
