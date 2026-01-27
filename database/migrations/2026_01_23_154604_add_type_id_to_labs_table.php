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
            // Add type_id column
            $table->foreignId('type_id')->nullable()->after('kode_lab')->constrained('lab_types')->nullOnDelete();

            // Drop old type column if it exists
            if (Schema::hasColumn('labs', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');

            // Restore old type column
            $table->string('type')->nullable()->after('kode_lab');
        });
    }
};
