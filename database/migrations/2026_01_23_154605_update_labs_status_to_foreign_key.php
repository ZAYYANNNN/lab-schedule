<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            // Add status_id column
            $table->foreignId('status_id')->nullable()->after('pj')->constrained('lab_statuses')->nullOnDelete();

            // Drop old status enum column
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');

            // Restore old status enum column
            $table->enum('status', ['Tersedia', 'Digunakan', 'Maintenance'])->default('Tersedia')->after('pj');
        });
    }
};
