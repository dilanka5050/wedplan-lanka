<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timelines', function (Blueprint $table) {
            $table->time('nekath_time')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('timelines', function (Blueprint $table) {
            $table->string('nekath_time')->nullable()->change();
        });
    }
};
