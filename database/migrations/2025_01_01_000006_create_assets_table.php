<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_labs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lab_id');
            $table->string('nama');
            $table->string('kode_aset')->nullable();
            $table->integer('jumlah')->default(1);
            $table->timestamps();

            $table->foreign('lab_id')->references('id')->on('labs')->cascadeOnDelete();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('aset_labs');
    }
};
