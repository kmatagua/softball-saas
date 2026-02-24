<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'league_id',
        'tournament_id',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
