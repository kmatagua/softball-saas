<?php

namespace App\Enums;

enum GameStage: string
{
    case REGULAR = 'regular';
    case QUARTERFINAL = 'quarterfinal';
    case SEMIFINAL = 'semifinal';
    case FINAL = 'final';
}