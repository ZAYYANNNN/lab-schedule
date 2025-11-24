<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lab_id');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // ini boleh unsigned

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('activity');

            $table->timestamps();

            $table->foreign('lab_id')->references('id')->on('labs')->cascadeOnDelete();
        });

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
