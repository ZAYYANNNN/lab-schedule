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
            $table->string('type')->default('praktikum')->after('kode_lab');
            $table->uuid('prodi_id')->nullable()->change();
            $table->string('prodi')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('prodi_id')->nullable()->change();
            $table->string('prodi')->nullable()->change();
            $table->uuid('lab_id')->nullable()->after('prodi_id');

            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);
            $table->dropColumn('lab_id');
            $table->uuid('prodi_id')->nullable(false)->change();
            $table->string('prodi')->nullable(false)->change();
        });

        Schema::table('labs', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->uuid('prodi_id')->nullable(false)->change();
            $table->string('prodi')->nullable(false)->change();
        });
    }
};
