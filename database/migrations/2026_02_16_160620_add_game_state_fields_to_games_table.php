<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->integer('current_inning')->default(1);
            $table->enum('half_inning', ['top', 'bottom'])->default('top');
            $table->integer('outs')->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->dropColumn([
                'current_inning',
                'half_inning',
                'outs'
            ]);
        });
    }
};