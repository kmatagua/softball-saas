<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_events', function (Blueprint $table) {

            $table->integer('rbi')->default(0);

            $table->foreignId('scored_player_id')
                  ->nullable()
                  ->constrained('players')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('game_events', function (Blueprint $table) {

            $table->dropColumn('rbi');
            $table->dropConstrainedForeignId('scored_player_id');
        });
    }
};