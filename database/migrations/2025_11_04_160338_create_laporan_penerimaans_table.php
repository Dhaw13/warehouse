<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_penerimaans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('barang_id')->nullable()->constrained()->onDelete('set null'); // ← Ubah jadi nullable
    $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('cascade'); // ← Tambah ini
    $table->string('nama_barang'); // ← Tambah ini untuk rejected
    $table->string('periode'); 
    $table->date('tanggal_cetak');
    $table->integer('total_barang');
    $table->enum('status', ['approved', 'rejected'])->default('approved'); // ← Tambah ini
    $table->text('catatan')->nullable(); // ← Tambah ini
    $table->string('file_laporan')->nullable();
    $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_penerimaans');
    }
};