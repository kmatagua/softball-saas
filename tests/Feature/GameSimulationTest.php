<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use App\Enums\GameStatus;
use Spatie\Permission\Models\Role;

class GameSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_inning_simulation_advancing_runners()
    {
        Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);

        // 1. Setup Data
        $league = League::create(['name' => 'Demo', 'slug' => 'demo', 'active' => true]);
        $tournament = Tournament::create(['league_id' => $league->id, 'name' => 'T', 'is_active' => true, 'groups_count' => 1]);
        $group = Group::create(['league_id' => $league->id, 'tournament_id' => $tournament->id, 'name' => 'G1']);

        $homeTeam = Team::create(['league_id' => $league->id, 'tournament_id' => $tournament->id, 'group_id' => $group->id, 'name' => 'Home']);
        $awayTeam = Team::create(['league_id' => $league->id, 'tournament_id' => $tournament->id, 'group_id' => $group->id, 'name' => 'Away']);

        // Create some players
        $homePlayers = [];
        $awayPlayers = [];
        for ($i = 1; $i <= 9; $i++) {
            $homePlayers[] = Player::create(['team_id' => $homeTeam->id, 'first_name' => 'H', 'last_name' => "Player$i"]);
            $awayPlayers[] = Player::create(['team_id' => $awayTeam->id, 'first_name' => 'A', 'last_name' => "Player$i"]);
        }

        $homePitcher = $homePlayers[8];
        $awayPitcher = $awayPlayers[8];

        $game = Game::create([
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'stage' => 'regular',
            'status' => GameStatus::LIVE,
            'home_score' => 0,
            'away_score' => 0,
            'current_inning' => 1,
            'half_inning' => 'top',
            'outs' => 0
        ]);

        $user = User::factory()->create();
        $user->assignRole('SuperAdmin');

        $this->actingAs($user, 'sanctum');

        // Bater 1 (Away) - Single
        $response = $this->postJson('/api/game-events', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'player_id' => $awayPlayers[0]->id,
            'pitcher_id' => $homePitcher->id,
            'event_type' => 'single'
        ]);

        $response->assertStatus(201);
        $response->assertStatus(201);
        $this->assertEquals($awayPlayers[0]->id, $response->json('bases.first'));

        // Bater 2 (Away) - Double
        $response = $this->postJson('/api/game-events', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'player_id' => $awayPlayers[1]->id,
            'pitcher_id' => $homePitcher->id,
            'event_type' => 'double'
        ]);

        $response->assertStatus(201);
        $this->assertEquals($awayPlayers[0]->id, $response->json('bases.third'));
        $this->assertEquals($awayPlayers[1]->id, $response->json('bases.second'));

        // Bater 3 (Away) - Homerun -> 3 runs
        $response = $this->postJson('/api/game-events', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'player_id' => $awayPlayers[2]->id,
            'pitcher_id' => $homePitcher->id,
            'event_type' => 'homerun'
        ]);

        $response->assertStatus(201);
        $this->assertEquals(3, $response->json('score.away'));
        $this->assertNull($response->json('bases.first'));

        // Bater 4 (Away) - Out
        $this->postJson('/api/game-events', ['game_id' => $game->id, 'team_id' => $awayTeam->id, 'player_id' => $awayPlayers[3]->id, 'pitcher_id' => $homePitcher->id, 'event_type' => 'out'])->assertStatus(201);
        $this->postJson('/api/game-events', ['game_id' => $game->id, 'team_id' => $awayTeam->id, 'player_id' => $awayPlayers[4]->id, 'pitcher_id' => $homePitcher->id, 'event_type' => 'out'])->assertStatus(201);

        // Bater 6 (Away) - Out -> Inning change
        $response = $this->postJson('/api/game-events', ['game_id' => $game->id, 'team_id' => $awayTeam->id, 'player_id' => $awayPlayers[5]->id, 'pitcher_id' => $homePitcher->id, 'event_type' => 'out']);

        $response->assertStatus(201);
        $this->assertEquals(0, $response->json('outs'));
        $this->assertEquals('bottom', $response->json('half'));
        $this->assertEquals(1, $response->json('inning'));

        // DB Assertions for RBI
        $totalRbi = \App\Models\GameEvent::where('game_id', $game->id)->sum('rbi');
        $this->assertEquals(3, $totalRbi);
    }
}
