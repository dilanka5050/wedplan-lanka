<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            // Null = use plan default. Set by admin to override for this wedding only.
            $table->unsignedInteger('custom_upload_limit')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('custom_upload_limit');
        });
    }
};
