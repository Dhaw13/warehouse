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
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('po_item_id')->constrained('purchase_order_items')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('set null'); // Link ke master barang
            $table->integer('jumlah_diterima'); // Jumlah aktual yang diterima
            $table->string('kualitas_valid'); // baik, rusak, dll
            $table->enum('status', ['verified', 'rejected'])->default('verified');
            $table->text('catatan')->nullable();
            $table->date('tanggal_verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_barangs');
    }
};