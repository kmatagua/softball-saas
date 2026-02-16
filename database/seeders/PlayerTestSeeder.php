<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;

class PlayerTestSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {

            for ($i = 1; $i <= 9; $i++) {

                Player::create([
                    'team_id' => $team->id,
                    'first_name' => 'Jugador',
                    'last_name' => $team->name . ' ' . $i,
                    'dni' => null,
                    'jersey_number' => $i
                ]);
            }
        }
    }
}