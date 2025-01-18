<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case AWAITING_RESPONSE = 'awaiting_response';
    case INTERVIEWING = 'interviewing';
    case OFFER = 'offer';
    case REJECTED = 'rejected';
    case HIRED = 'hired';
    case WITHDRAWN = 'withdrawn';
}
