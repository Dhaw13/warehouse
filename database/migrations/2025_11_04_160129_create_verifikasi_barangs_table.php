<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang'); // relasi ke barangs
            $table->integer('jumlah_valid');
            $table->string('kualitas_valid');
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('catatan')->nullable();
            $table->date('tanggal_verifikasi');
            $table->timestamps();

            // Foreign key (opsional, tapi disarankan)
            $table->foreign('id_barang')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_barangs');
    }
};