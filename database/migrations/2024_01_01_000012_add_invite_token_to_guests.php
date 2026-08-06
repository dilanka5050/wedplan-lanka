<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('invite_token', 64)->nullable()->unique()->after('id');
        });

        // Backfill existing guests
        \App\Models\Guest::whereNull('invite_token')->each(function ($guest) {
            $guest->updateQuietly(['invite_token' => Str::random(32)]);
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('invite_token');
        });
    }
};
