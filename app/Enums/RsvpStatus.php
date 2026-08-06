<?php

namespace App\Enums;

enum RsvpStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case MAYBE = 'maybe';
}
