<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\User;
use App\Enums\GameStatus;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameEventTest extends TestCase
{
    use RefreshDatabase;

    protected $game;
    protected $homeTeam;
    protected $awayTeam;
    protected $league;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar roles de Spatie
        $role = Role::create(['name' => 'Scorekeeper', 'guard_name' => 'web']);

        // Crear registros manualmente
        $this->user = User::factory()->create();
        $this->user->assignRole($role);
        
        $this->league = League::create([
            'name' => 'Test League',
            'slug' => 'test-league',
            'active' => true
        ]);

        $tournament = Tournament::create([
            'league_id' => $this->league->id,
            'name' => 'Test Tournament',
            'slug' => 'test-tournament',
            'active' => true,
            'settings' => []
        ]);

        $group = Group::create([
            'league_id' => $this->league->id,
            'tournament_id' => $tournament->id,
            'name' => 'Group A'
        ]);

        $this->homeTeam = Team::create([
            'league_id' => $this->league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id,
            'name' => 'Home Team'
        ]);

        $this->awayTeam = Team::create([
            'league_id' => $this->league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id,
            'name' => 'Away Team'
        ]);

        $this->game = Game::create([
            'league_id' => $this->league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $group->id,
            'home_team_id' => $this->homeTeam->id,
            'away_team_id' => $this->awayTeam->id,
            'status' => GameStatus::LIVE,
            'current_inning' => 1,
            'half_inning' => 'top',
        ]);
    }

    public function test_single_updates_bases_and_score()
    {
        $batter = Player::create([
            'team_id' => $this->awayTeam->id,
            'league_id' => $this->league->id,
            'first_name' => 'Batter',
            'last_name' => 'One',
            'active' => true
        ]);

        $pitcher = Player::create([
            'team_id' => $this->homeTeam->id,
            'league_id' => $this->league->id,
            'first_name' => 'Pitcher',
            'last_name' => 'One',
            'active' => true
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/game-events', [
            'game_id' => $this->game->id,
            'team_id' => $this->awayTeam->id,
            'player_id' => $batter->id,
            'pitcher_id' => $pitcher->id,
            'event_type' => 'single'
        ]);

        $response->assertStatus(201);
        $this->assertEquals($batter->id, $response->json('bases.first.id'));
        $this->assertEquals($batter->last_name, $response->json('bases.first.last_name'));
    }

    public function test_homerun_updates_score()
    {
        $batter = Player::create([
            'team_id' => $this->awayTeam->id,
            'league_id' => $this->league->id,
            'first_name' => 'Batter',
            'last_name' => 'One',
            'active' => true
        ]);
        
        // Single first
        $this->actingAs($this->user)->postJson('/api/game-events', [
            'game_id' => $this->game->id,
            'team_id' => $this->awayTeam->id,
            'player_id' => $batter->id,
            'event_type' => 'single'
        ]);

        $batter2 = Player::create([
            'team_id' => $this->awayTeam->id,
            'league_id' => $this->league->id,
            'first_name' => 'Batter',
            'last_name' => 'Two',
            'active' => true
        ]);
        
        // HR
        $response = $this->actingAs($this->user)->postJson('/api/game-events', [
            'game_id' => $this->game->id,
            'team_id' => $this->awayTeam->id,
            'player_id' => $batter2->id,
            'event_type' => 'homerun'
        ]);

        $response->assertStatus(201);
        $this->assertEquals(2, $this->game->fresh()->away_score);
    }
}
