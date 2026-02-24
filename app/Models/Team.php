<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToLeague;

class Team extends Model
{
    use BelongsToLeague;
    protected $fillable = [
        'name',
        'league_id',
        'tournament_id',
        'group_id',
        'image'
    ];

    public function homeGames()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
