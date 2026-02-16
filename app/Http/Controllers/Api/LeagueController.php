<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\User;
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
}