<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->integer('points_per_win')->default(3);
            $table->integer('points_per_draw')->default(1);
            $table->integer('points_per_loss')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn([
                'points_per_win',
                'points_per_draw',
                'points_per_loss'
            ]);
        });
    }
};