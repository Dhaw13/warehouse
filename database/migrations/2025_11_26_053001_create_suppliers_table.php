<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel suppliers sudah ada
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('nama_supplier')->unique();
                $table->string('kontak')->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }

        // Update tabel barangs untuk relasi ke supplier
        Schema::table('barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('barangs', 'id_supplier')) {
                $table->unsignedBigInteger('id_supplier')->nullable()->after('tanggal_masuk');
                $table->foreign('id_supplier')->references('id')->on('suppliers')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['id_supplier']);
            $table->dropColumn('id_supplier');
        });
        
        Schema::dropIfExists('suppliers');
    }
};