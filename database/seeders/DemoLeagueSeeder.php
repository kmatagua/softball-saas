<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\Game;

class DemoLeagueSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // CREAR LIGA
        // ==========================

        $league = League::create([
            'name'   => 'Liga Demo 2026',
            'slug'   => 'liga-demo-2026',
            'active' => true,
        ]);

        // ==========================
        // CREAR TORNEO (MODELO B 3-1-0)
        // ==========================

        $tournament = Tournament::create([
            'league_id'           => $league->id,
            'name'                => 'Categoría Única',
            'groups_count'        => 2,
            'qualifies_per_group' => 2,
            'points_per_win'      => 3,
            'points_per_draw'     => 1,
            'points_per_loss'     => 0,
            'is_active'           => true,
        ]);

        $groupNames = ['Grupo A', 'Grupo B'];

        foreach ($groupNames as $groupName) {

            // ==========================
            // CREAR GRUPO
            // ==========================

            $group = Group::create([
                'league_id'     => $league->id,
                'tournament_id' => $tournament->id,
                'name'          => $groupName,
            ]);

            $teams = [];

            // ==========================
            // CREAR 9 EQUIPOS
            // ==========================

            for ($i = 1; $i <= 9; $i++) {

                $team = Team::create([
                    'league_id'     => $league->id,
                    'tournament_id' => $tournament->id,
                    'group_id'      => $group->id,
                    'name'          => "{$groupName} Equipo {$i}",
                ]);

                $teams[] = $team;
            }

            // ==========================
            // ROUND ROBIN COMPLETO
            // ==========================

            $totalTeams = count($teams);

            for ($i = 0; $i < $totalTeams; $i++) {

                for ($j = $i + 1; $j < $totalTeams; $j++) {

                    // Generar marcador variado
                    $homeScore = rand(0, 12);
                    $awayScore = rand(0, 12);

                    Game::create([
                        'league_id'     => $league->id,
                        'tournament_id' => $tournament->id,
                        'group_id'      => $group->id,
                        'home_team_id'  => $teams[$i]->id,
                        'away_team_id'  => $teams[$j]->id,
                        'stage'         => 'regular',
                        'status'        => 'finished',
                        'home_score'    => $homeScore,
                        'away_score'    => $awayScore,
                        'game_date'     => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }
    }
}