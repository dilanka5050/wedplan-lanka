<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('bride_name');
            $table->string('groom_name');
            $table->string('bride_surname');
            $table->string('groom_surname');
            $table->string('wedding_day');        // e.g. "Sunday 11th March 2026"
            $table->string('wedding_location');   // e.g. "The Grand Royal Hotel, Kurunegala"
            $table->string('duration_from');      // e.g. "09.00 A.M"
            $table->string('duration_to');        // e.g. "04.00 PM"
            $table->string('poruwa_time');        // e.g. "09.18 A.M"
            $table->string('template')->default('card1'); // card1 or card2
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
