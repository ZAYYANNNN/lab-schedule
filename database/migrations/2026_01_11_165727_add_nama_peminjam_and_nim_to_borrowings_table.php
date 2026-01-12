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
            $table->string('nama_peminjam')->after('user_id');
            $table->string('nim')->after('nama_peminjam');

            // user_id stays as the 'creator/admin' who recorded it
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'nim']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
