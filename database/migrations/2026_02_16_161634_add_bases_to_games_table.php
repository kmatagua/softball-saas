<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->boolean('first_base')->default(false);
            $table->boolean('second_base')->default(false);
            $table->boolean('third_base')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {

            $table->dropColumn([
                'first_base',
                'second_base',
                'third_base'
            ]);
        });
    }
};