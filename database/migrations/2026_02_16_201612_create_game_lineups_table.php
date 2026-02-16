<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_lineups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');

            $table->integer('batting_order');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['game_id', 'team_id', 'batting_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_lineups');
    }
};