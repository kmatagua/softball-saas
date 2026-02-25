<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLineup extends Model
{
    protected $fillable = [
        'game_id',
        'team_id',
        'player_id',
        'batting_order',
        'field_position', 
        'is_starter', 
        'is_active'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}