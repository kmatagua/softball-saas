<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;

class GamePolicy
{
    /**
     * Determina si el usuario puede crear un juego en una liga.
     */
    public function create(User $user, int $leagueId): bool
    {
        return
            $user->hasLeagueRole($leagueId, 'LeagueAdmin') ||
            $user->hasLeagueRole($leagueId, 'SuperAdmin');
    }

    /**
     * Determina si el usuario puede ver juegos.
     * (Aquí puedes refinar luego por liga si quieres)
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
}