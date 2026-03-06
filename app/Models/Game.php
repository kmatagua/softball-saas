<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToLeague;
use App\Enums\GameStage;
use App\Enums\GameStatus;

class Game extends Model
{
    use BelongsToLeague;

    protected $fillable = [
        'league_id',
        'tournament_id',
        'group_id',
        'home_team_id',
        'away_team_id',
        'stage',
        'status',
        'home_score',
        'away_score',
        'game_date',
        'current_inning',
        'half_inning',
        'outs',
        'first_base_player_id',
        'second_base_player_id',
        'third_base_player_id',
        'current_batter_home',
        'current_batter_away',
    ];

    protected $casts = [
        'stage' => GameStage::class ,
        'status' => GameStatus::class ,
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class , 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class , 'away_team_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }

    public function lineups()
    {
        return $this->hasMany(GameLineup::class);
    }
}