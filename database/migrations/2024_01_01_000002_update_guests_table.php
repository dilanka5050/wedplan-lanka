<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('plus_ones');
            $table->string('invitation_type')->default('single')->after('phone'); // single, couple, family
            $table->unsignedTinyInteger('total_invited')->default(1)->after('invitation_type');
            $table->unsignedTinyInteger('rsvp_confirmed')->default(0)->after('total_invited');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['invitation_type', 'total_invited', 'rsvp_confirmed']);
            $table->integer('plus_ones')->default(0);
        });
    }
};
