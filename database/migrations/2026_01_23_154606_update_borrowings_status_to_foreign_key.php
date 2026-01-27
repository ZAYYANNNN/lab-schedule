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
        Schema::table('borrowings', function (Blueprint $table) {
            // Add status_id column
            $table->foreignId('status_id')->nullable()->after('return_time')->constrained('borrowing_statuses')->nullOnDelete();

            // Drop old status enum column
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');

            // Restore old status enum column
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending')->after('return_time');
        });
    }
};
