<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case AWAITING_RESPONSE = 'awaiting_response';
    case INTERVIEWING = 'interviewing';
    case REJECTED = 'rejected';
    case OFFER = 'offer';
    case OFFER_ACCEPTED = 'offer_accepted';
    case WITHDRAWN = 'withdrawn';
}
