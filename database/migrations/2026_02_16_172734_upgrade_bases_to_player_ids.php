<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {

            // eliminar booleanos antiguos
            $table->dropColumn(['first_base', 'second_base', 'third_base']);

            // nuevas columnas con jugador
            $table->foreignId('first_base_player_id')
                  ->nullable()
                  ->constrained('players')
                  ->nullOnDelete();

            $table->foreignId('second_base_player_id')
                  ->nullable()
                  ->constrained('players')
                  ->nullOnDelete();

            $table->foreignId('third_base_player_id')
                  ->nullable()
                  ->constrained('players')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->dropConstrainedForeignId('first_base_player_id');
            $table->dropConstrainedForeignId('second_base_player_id');
            $table->dropConstrainedForeignId('third_base_player_id');

            $table->boolean('first_base')->default(false);
            $table->boolean('second_base')->default(false);
            $table->boolean('third_base')->default(false);
        });
    }
};