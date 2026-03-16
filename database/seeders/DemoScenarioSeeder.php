<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\User;
use App\Enums\GameStage;
use App\Enums\GameStatus;
use Illuminate\Support\Facades\Hash;

class DemoScenarioSeeder extends Seeder
{
    public function run()
    {
        // 0. Usuario de prueba
        $user = User::updateOrCreate(
            ['email' => 'admin@softball.com'],
            [
                'name' => 'Admin Test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 1. Liga
        $league = League::create([
            'name' => 'Liga de Prueba Antigravity',
            'slug' => Str::slug('Liga de Prueba Antigravity') . '-' . time()
        ]);

        // 2. Torneo
        $tournament = Tournament::create([
            'league_id' => $league->id,
            'name' => 'Copa de Oro 2026',
            'is_active' => true
        ]);

        // 3. Grupo
        $group = Group::create([
            'tournament_id' => $tournament->id,
            'name' => 'Grupo A',
            'league_id' => $league->id
        ]);

        // 4. Equipos
        $teamHome = Team::create([
            'name' => 'Tigres del Licey',
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id
        ]);
        $teamAway = Team::create([
            'name' => 'Águilas Cibaeñas',
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id
        ]);

        // 5. Jugadores
        for ($i = 1; $i <= 15; $i++) {
            Player::create([
                'team_id' => $teamHome->id,
                'first_name' => 'Jugador',
                'last_name' => 'Home ' . $i,
                'jersey_number' => $i
            ]);
            Player::create([
                'team_id' => $teamAway->id,
                'first_name' => 'Jugador',
                'last_name' => 'Away ' . $i,
                'jersey_number' => $i
            ]);
        }

        // 6. Juego
        $game = Game::create([
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id,
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id,
            'stage' => GameStage::REGULAR,
            'status' => GameStatus::SCHEDULED,
            'game_date' => now(),
            'current_inning' => 1,
            'half_inning' => 'top',
            'home_score' => 0,
            'away_score' => 0,
            'outs' => 0
        ]);

        $this->command->info('Escenario de prueba creado con éxito.');
        $this->command->info('Admin: admin@softball.com / password');
        $this->command->info('Game ID: ' . $game->id);
    }
}
