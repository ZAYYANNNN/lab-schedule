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
            $table->time('borrow_time')->nullable()->after('borrow_date');
            $table->time('return_time')->nullable()->after('return_date');
            $table->dateTime('actual_return_datetime')->nullable()->after('return_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['borrow_time', 'return_time', 'actual_return_datetime']);
        });
    }
};
