<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'team_id',
        'first_name',
        'last_name',
        'dni',
        'jersey_number',
        'batting_order' // 👈 agregado
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function gameEvents()
    {
        return $this->hasMany(GameEvent::class);
    }

    // 👇 Helper opcional útil
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}