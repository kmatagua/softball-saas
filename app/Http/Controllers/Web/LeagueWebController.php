<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeagueWebController extends Controller
{
    public function index()
    {
        $leagues = League::latest()->get();
        return view('admin.leagues.index', compact('leagues'));
    }

    public function create()
    {
        return view('admin.leagues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        League::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'active' => true
        ]);

        return redirect()
            ->route('admin.leagues.index')
            ->with('success', 'Liga creada correctamente');
    }

    public function show(League $league)
    {
        $league->load(['teams.players', 'groups']);

        return view('admin.leagues.show', compact('league'));
    }
}