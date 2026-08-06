<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seating_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('capacity');
            $table->timestamps();
        });

        Schema::create('seating_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seating_table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique('guest_id'); // one guest can only be at one table
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seating_assignments');
        Schema::dropIfExists('seating_tables');
    }
};
