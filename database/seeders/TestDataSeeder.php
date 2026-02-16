<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Models\Group;
use App\Models\Team;
use App\Models\Game;
use App\Enums\GameStage;
use App\Enums\GameStatus;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Liga
        $league = League::create([
            'name' => 'Liga Demo Lima',
            'slug' => Str::slug('Liga Demo Lima') . '-' . uniqid(),
            'active' => true
        ]);

        // Crear Grupo A
        $group = Group::create([
            'league_id' => $league->id,
            'name' => 'Grupo A'
        ]);

        // Crear Equipos
        $team1 = Team::create([
            'league_id' => $league->id,
            'group_id' => $group->id,
            'name' => 'Tigres'
        ]);

        $team2 = Team::create([
            'league_id' => $league->id,
            'group_id' => $group->id,
            'name' => 'Leones'
        ]);

        $team3 = Team::create([
            'league_id' => $league->id,
            'group_id' => $group->id,
            'name' => 'Águilas'
        ]);

        // Crear Juego LIVE listo para eventos
        Game::create([
            'league_id' => $league->id,
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'stage' => GameStage::REGULAR,
            'status' => GameStatus::LIVE,
            'home_score' => 0,
            'away_score' => 0,
            'game_date' => now()
        ]);

        Game::create([
            'league_id' => $league->id,
            'home_team_id' => $team2->id,
            'away_team_id' => $team3->id,
            'stage' => GameStage::REGULAR,
            'status' => GameStatus::LIVE,
            'home_score' => 0,
            'away_score' => 0,
            'game_date' => now()
        ]);
    }
}