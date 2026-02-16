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
        'home_team_id',
        'away_team_id',
        'stage',
        'status',
        'home_score',
        'away_score',
        'game_date'
    ];

    protected $casts = [
        'stage' => GameStage::class,
        'status' => GameStatus::class,
        'game_date' => 'datetime',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }
}