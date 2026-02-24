<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('tournament_id')
                  ->nullable()
                  ->after('league_id')
                  ->constrained()
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tournament_id');
        });
    }
};
