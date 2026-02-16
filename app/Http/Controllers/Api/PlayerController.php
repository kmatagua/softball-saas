<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\GameEvent;

class PlayerController extends Controller
{
    public function advancedStats($id)
    {
        $player = Player::findOrFail($id);

        $events = GameEvent::where('player_id', $player->id)->get();

        $singles = $events->where('event_type', 'single')->count();
        $doubles = $events->where('event_type', 'double')->count();
        $triples = $events->where('event_type', 'triple')->count();
        $homeruns = $events->where('event_type', 'homerun')->count();
        $outs = $events->where('event_type', 'out')->count();
        $walks = $events->where('event_type', 'walk')->count();
        $hbp = $events->where('event_type', 'hbp')->count();

        $hits = $singles + $doubles + $triples + $homeruns;
        $atBats = $hits + $outs;
        $plateAppearances = $atBats + $walks + $hbp;

        $totalBases =
            ($singles * 1) +
            ($doubles * 2) +
            ($triples * 3) +
            ($homeruns * 4);

        $runs = GameEvent::where('scored_player_id', $player->id)->count();
        $rbi = $events->sum('rbi');

        $avg = $atBats > 0 ? round($hits / $atBats, 3) : 0;
        $slg = $atBats > 0 ? round($totalBases / $atBats, 3) : 0;
        $obp = $plateAppearances > 0
            ? round(($hits + $walks + $hbp) / $plateAppearances, 3)
            : 0;

        $ops = round($obp + $slg, 3);

        return response()->json([
            'player' => [
                'id' => $player->id,
                'name' => $player->first_name . ' ' . $player->last_name
            ],
            'stats' => [
                'AB' => $atBats,
                'PA' => $plateAppearances,
                'H' => $hits,
                'BB' => $walks,
                'HBP' => $hbp,
                'R' => $runs,
                'RBI' => $rbi,
                'AVG' => $avg,
                'OBP' => $obp,
                'SLG' => $slg,
                'OPS' => $ops
            ]
        ]);
    }
}