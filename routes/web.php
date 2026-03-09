<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LeagueWebController;
use App\Http\Controllers\Web\GroupWebController;
use App\Http\Controllers\Web\TeamWebController;
use App\Http\Controllers\Web\PlayerWebController;
use App\Http\Controllers\Web\GameWebController;
use App\Http\Controllers\Web\TournamentWebController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ScorekeeperDashboardController;

Route::get('/', function () {
    return redirect()->route('public.home');
});

// ===================== RUTAS DE AUTENTICACIÓN =====================
Route::get('/login', [AuthController::class , 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class , 'login'])->name('login.post');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

// ===================== RUTAS PÚBLICAS (FAN VIEW) =====================
Route::get('/live', function () {
    $leagues = \App\Models\League::where('active', true)->get();
    return view('public.index', compact('leagues'));
})->name('public.home');

Route::get('/live/games/{game}', function (\App\Models\Game $game) {
    $game->load(['league', 'tournament', 'homeTeam', 'awayTeam']);
    return view('public.games.live', compact('game'));
})->name('public.games.live');

// ===================== RUTAS ADMIN =====================
Route::prefix('admin')->middleware(['auth'])->group(function () {

    // ===================== LEAGUES =====================

    Route::get('/leagues', [LeagueWebController::class , 'index'])
        ->name('admin.leagues.index');

    Route::get('/leagues/create', [LeagueWebController::class , 'create'])
        ->name('admin.leagues.create');

    Route::post('/leagues', [LeagueWebController::class , 'store'])
        ->name('admin.leagues.store');

    Route::get('/leagues/{league}', [LeagueWebController::class , 'show'])
        ->name('admin.leagues.show');

    Route::get('/leagues/{league}/edit', [LeagueWebController::class , 'edit'])
        ->name('admin.leagues.edit');

    Route::put('/leagues/{league}', [LeagueWebController::class , 'update'])
        ->name('admin.leagues.update');

    Route::delete('/leagues/{league}', [LeagueWebController::class , 'destroy'])
        ->name('admin.leagues.destroy');


    // ===================== TOURNAMENTS =====================

    Route::get('/leagues/{league}/tournaments',
    [TournamentWebController::class , 'index'])
        ->name('admin.tournaments.index');

    Route::get('/leagues/{league}/tournaments/create',
    [TournamentWebController::class , 'create'])
        ->name('admin.tournaments.create');

    Route::post('/leagues/{league}/tournaments',
    [TournamentWebController::class , 'store'])
        ->name('admin.tournaments.store');

    Route::delete('/leagues/{league}/tournaments/{tournament}',
    [TournamentWebController::class , 'destroy'])
        ->name('admin.tournaments.destroy');

    Route::get('/leagues/{league}/tournaments/{tournament}/standings',
    [TournamentWebController::class , 'standings'])
        ->name('admin.tournaments.standings');

    Route::get('/leagues/{league}/tournaments/{tournament}/edit',
    [TournamentWebController::class , 'edit']
    )->name('admin.tournaments.edit');

    Route::put('/leagues/{league}/tournaments/{tournament}',
    [TournamentWebController::class , 'update']
    )->name('admin.tournaments.update');

    Route::get('/leagues/{league}/tournaments/{tournament}',
    [TournamentWebController::class , 'show']
    )->name('admin.tournaments.show');

    Route::post('/leagues/{league}/tournaments/{tournament}/generate-playoffs',
    [TournamentWebController::class , 'generatePlayoffs']
    )->name('admin.tournaments.generatePlayoffs');


    // ===================== GROUPS =====================

    Route::get('/leagues/{league}/groups/create', [GroupWebController::class , 'create'])
        ->name('admin.groups.create');

    Route::post('/leagues/{league}/groups', [GroupWebController::class , 'store'])
        ->name('admin.groups.store');

    Route::get('/leagues/{league}/groups/{group}/edit', [GroupWebController::class , 'edit'])
        ->name('admin.groups.edit');

    Route::put('/leagues/{league}/groups/{group}', [GroupWebController::class , 'update'])
        ->name('admin.groups.update');

    Route::delete('/leagues/{league}/groups/{group}', [GroupWebController::class , 'destroy'])
        ->name('admin.groups.destroy');


    // ===================== TEAMS (AHORA DENTRO DE TOURNAMENT) =====================

    // Vista general de equipos por Liga (Nuevo)
    Route::get('/leagues/{league}/teams', [TeamWebController::class , 'index'])
        ->name('admin.teams.index');

    Route::get('/leagues/{league}/tournaments/{tournament}/teams/create',
    [TeamWebController::class , 'create'])
        ->name('admin.teams.create');

    Route::post('/leagues/{league}/tournaments/{tournament}/teams',
    [TeamWebController::class , 'store'])
        ->name('admin.teams.store');


    // ===================== TEAMS (EDIT / SHOW / DELETE) =====================

    Route::get('/leagues/{league}/teams/{team}', [TeamWebController::class , 'show'])
        ->name('admin.teams.show');

    Route::get('/leagues/{league}/teams/{team}/edit', [TeamWebController::class , 'edit'])
        ->name('admin.teams.edit');

    Route::put('/leagues/{league}/teams/{team}', [TeamWebController::class , 'update'])
        ->name('admin.teams.update');

    Route::delete('/leagues/{league}/teams/{team}', [TeamWebController::class , 'destroy'])
        ->name('admin.teams.destroy');


    // ===================== PLAYERS =====================

    // Vista general de jugadores por Liga (Nuevo)
    Route::get('/leagues/{league}/players', [PlayerWebController::class , 'index'])
        ->name('admin.players.index');

    Route::get('/leagues/{league}/teams/{team}/players/create',
    [PlayerWebController::class , 'create'])
        ->name('admin.players.create');

    Route::post('/leagues/{league}/teams/{team}/players',
    [PlayerWebController::class , 'store'])
        ->name('admin.players.store');

    Route::get('/leagues/{league}/teams/{team}/players/{player}/edit',
    [PlayerWebController::class , 'edit'])
        ->name('admin.players.edit');

    Route::put('/leagues/{league}/teams/{team}/players/{player}',
    [PlayerWebController::class , 'update'])
        ->name('admin.players.update');

    Route::delete('/leagues/{league}/teams/{team}/players/{player}',
    [PlayerWebController::class , 'destroy'])
        ->name('admin.players.destroy');

    Route::get('/leagues/{league}/teams/{team}/players/{player}',
    [PlayerWebController::class , 'show'])
        ->name('admin.players.show');


    // ==============================
    // GAMES ADMIN COMPLETO
    // ==============================

    // Vista general de juegos por Liga (Nuevo)
    Route::get('/leagues/{league}/games', [GameWebController::class , 'leagueIndex'])
        ->name('admin.games.leagueIndex');

    Route::get(
        '/leagues/{league}/tournaments/{tournament}/games',
    [GameWebController::class , 'adminIndex']
    )->name('admin.games.index');

    Route::get(
        '/leagues/{league}/tournaments/{tournament}/games/create',
    [GameWebController::class , 'create']
    )->name('admin.games.create');

    Route::post(
        '/leagues/{league}/tournaments/{tournament}/games',
    [GameWebController::class , 'store']
    )->name('admin.games.store');

    Route::get(
        '/leagues/{league}/tournaments/{tournament}/games/{game}',
    [GameWebController::class , 'adminShow']
    )->name('admin.games.show');

    Route::get(
        '/leagues/{league}/tournaments/{tournament}/games/{game}/edit',
    [GameWebController::class , 'edit']
    )->name('admin.games.edit');

    Route::put(
        '/leagues/{league}/tournaments/{tournament}/games/{game}',
    [GameWebController::class , 'update']
    )->name('admin.games.update');

    Route::delete(
        '/leagues/{league}/tournaments/{tournament}/games/{game}',
    [GameWebController::class , 'destroy']
    )->name('admin.games.destroy');

    // ==============================
    // SCOREKEEPER CONSOLE (VUE APP)
    // ==============================

    Route::get('/scorekeeper/dashboard', [ScorekeeperDashboardController::class , 'index'])
        ->name('admin.scorekeeper.dashboard');

    Route::get(
        '/leagues/{league}/tournaments/{tournament}/games/{game}/scorekeeper',
    [GameWebController::class , 'scorekeeper']
    )->name('admin.scorekeeper.index');

});