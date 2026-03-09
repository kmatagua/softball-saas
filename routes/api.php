<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GameEventController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\GameLineupController;

/* |-------------------------------------------------------------------------- | Rutas Públicas |-------------------------------------------------------------------------- */

Route::post('/login', [AuthController::class , 'login']);

// 🔥 Games Public Data
Route::get('/games', [GameController::class , 'index']);
Route::get('/games/{id}', [GameController::class , 'show'])->name('api.games.show');

/* |-------------------------------------------------------------------------- | Rutas Protegidas |-------------------------------------------------------------------------- */

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class , 'logout']);

    /*
     |--------------------------------------------------------------------------
     | SuperAdmin
     |--------------------------------------------------------------------------
     */

    Route::middleware(['role:SuperAdmin'])->group(function () {

            Route::post('/leagues', [LeagueController::class , 'store']);
            Route::get('/leagues', [LeagueController::class , 'index']);

        }
        );

        /*
     |--------------------------------------------------------------------------
     | SuperAdmin + LeagueAdmin + Scorekeeper
     |--------------------------------------------------------------------------
     */

        Route::middleware(['role:SuperAdmin|LeagueAdmin|Scorekeeper'])->group(function () {

            Route::get('/teams', [TeamController::class , 'index']);
            Route::post('/teams', [TeamController::class , 'store']);

            // 🔥 Games mutations
            Route::post('/games', [GameController::class , 'store']);
            Route::post('/games/{game}/start', [GameController::class , 'start']);

            Route::post('/game-events', [GameEventController::class , 'store']);
            Route::put('/game-events/{gameEvent}', [GameEventController::class , 'update']);
            Route::delete('/game-events/{gameEvent}', [GameEventController::class , 'destroy']);

            Route::get('/groups/{id}/standings', [GroupController::class , 'standings']);

            Route::get('/players/{id}/stats', [PlayerController::class , 'stats']);

            Route::get('/leagues/{id}/leaderboard', [LeagueController::class , 'leaderboard']);

            Route::get('/players/{id}/advanced-stats', [PlayerController::class , 'advancedStats']);
        }
        );

        /*
     |--------------------------------------------------------------------------
     | LeagueAdmin
     |--------------------------------------------------------------------------
     */

        Route::middleware(['role:LeagueAdmin'])->group(function () {

            Route::get('/my-league', [LeagueController::class , 'myLeague']);

        }
        );

        Route::post('/save-lineups', [GameLineupController::class , 'store']);
    });