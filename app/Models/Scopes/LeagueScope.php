<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class LeagueScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if (!$user->hasRole('SuperAdmin') && $user->league_id) {
            $builder->where('league_id', $user->league_id);
        }
    }
}