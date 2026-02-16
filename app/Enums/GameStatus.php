<?php

namespace App\Enums;

enum GameStatus: string
{
    case SCHEDULED = 'scheduled';
    case LIVE = 'live';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';
}