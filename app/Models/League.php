<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'active',
        'image'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }
}
