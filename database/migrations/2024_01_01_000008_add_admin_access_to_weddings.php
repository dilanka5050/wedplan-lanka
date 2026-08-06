<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->boolean('allow_admin_access')->default(false)->after('custom_upload_limit');
            $table->foreignId('last_accessed_by')->nullable()->constrained('users')->nullOnDelete()->after('allow_admin_access');
            $table->timestamp('last_accessed_at')->nullable()->after('last_accessed_by');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropForeign(['last_accessed_by']);
            $table->dropColumn(['allow_admin_access', 'last_accessed_by', 'last_accessed_at']);
        });
    }
};
