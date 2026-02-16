<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
{
    protected $table = 'game_events';

    protected $fillable = [
        'game_id',
        'team_id',
        'inning',
        'event_type',
        'runs'
    ];

    protected $casts = [
        'game_id' => 'integer',
        'team_id' => 'integer',
        'inning' => 'integer',
        'runs' => 'integer'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}