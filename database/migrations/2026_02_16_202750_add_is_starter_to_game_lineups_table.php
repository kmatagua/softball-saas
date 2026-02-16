<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_lineups', function (Blueprint $table) {
            $table->boolean('is_starter')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('game_lineups', function (Blueprint $table) {
            $table->dropColumn('is_starter');
        });
    }
};