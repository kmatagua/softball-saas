<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->unsignedInteger('groups_count')
                ->default(1)
                ->after('name');
        });
    }

    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('groups_count');
        });
    }
};
