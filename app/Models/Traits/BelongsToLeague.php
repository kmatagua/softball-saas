<?php

namespace App\Models\Traits;

use App\Models\Scopes\LeagueScope;
use App\Models\League;

trait BelongsToLeague
{
    protected static function bootBelongsToLeague()
    {
        static::addGlobalScope(new LeagueScope);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}