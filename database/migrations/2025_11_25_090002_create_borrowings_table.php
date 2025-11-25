<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();

            // Kunci Asing ke tabel aset_labs
            $table->foreignId('asset_lab_id')->constrained('aset_labs')->onDelete('cascade');
            
            // Kunci Asing ke tabel users (mahasiswa/peminjam)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->integer('quantity')->comment('Jumlah aset yang dipinjam');
            
            $table->date('borrow_date');
            $table->date('return_date')->comment('Tanggal rencana pengembalian');
            $table->date('actual_return_date')->nullable()->comment('Tanggal aktual pengembalian');
            
            // Status peminjaman: Dipinjam, Dikembalikan, Terlambat, dll.
            $table->string('status')->default('Dipinjam'); 
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};