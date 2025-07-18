<?php

namespace App\Enums;

enum EventStatus: int
{
    case Pending = 0;
    case Primed = 1;
    case Scheduled = 2;
    case Logged = 3;
    case LoggedLate = 4;
    case Missed = 5;
    case Cancelled = 6;
}
