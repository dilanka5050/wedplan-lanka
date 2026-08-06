<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('venue_map_url')->nullable()->after('wedding_location');
            $table->string('bride_phone')->nullable()->after('poruwa_time');
            $table->string('groom_phone')->nullable()->after('bride_phone');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['venue_map_url', 'bride_phone', 'groom_phone']);
        });
    }
};
