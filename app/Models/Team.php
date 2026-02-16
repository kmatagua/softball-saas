<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToLeague;

class Team extends Model
{
    use BelongsToLeague;
    protected $fillable = [
        'name',
        'league_id'
    ];

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
