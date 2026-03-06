<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\User;
use App\Models\Player;
use App\Models\GameEvent;
use App\Enums\GameStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LeagueController extends Controller
{
    public function index()
    {
        return League::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6'
        ]);

        $league = League::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'active' => true
        ]);

        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'league_id' => $league->id
        ]);

        $admin->assignRole('LeagueAdmin');

        return response()->json([
            'league' => $league,
            'admin_user' => $admin
        ], 201);
    }

    public function myLeague(Request $request)
    {
        $user = $request->user();

        if (!$user->league_id) {
            return response()->json([
                'message' => 'Este usuario no pertenece a ninguna liga'
            ], 403);
        }

        $league = League::find($user->league_id);

        return response()->json($league);
    }

    /**
     * Leaderboard por liga
     */
    public function leaderboard($leagueId)
    {
        $league = League::findOrFail($leagueId);

        $players = Player::whereHas('team', function ($query) use ($leagueId) {
            $query->where('league_id', $leagueId);
        })->get();

        $leaderboard = [];

        foreach ($players as $player) {

            $events = GameEvent::where('player_id', $player->id)
                ->whereHas('game', function ($query) {
                $query->where('status', GameStatus::FINISHED);
            })
                ->get();

            $singles = $events->where('event_type', 'single')->count();
            $doubles = $events->where('event_type', 'double')->count();
            $triples = $events->where('event_type', 'triple')->count();
            $homeruns = $events->where('event_type', 'homerun')->count();
            $outs = $events->where('event_type', 'out')->count();

            $hits = $singles + $doubles + $triples + $homeruns;
            $ab = $hits + $outs;

            if ($ab === 0) {
                continue;
            }

            $avg = round($hits / $ab, 3);

            $leaderboard[] = [
                'player_id' => $player->id,
                'name' => $player->first_name . ' ' . $player->last_name,
                'team' => $player->team->name,
                'AVG' => $avg,
                'HR' => $homeruns,
                'H' => $hits
            ];
        }

        $topAvg = collect($leaderboard)->sortByDesc('AVG')->take(5)->values();
        $topHr = collect($leaderboard)->sortByDesc('HR')->take(5)->values();
        $topHits = collect($leaderboard)->sortByDesc('H')->take(5)->values();

        return response()->json([
            'top_avg' => $topAvg,
            'top_hr' => $topHr,
            'top_hits' => $topHits
        ]);
    }
}