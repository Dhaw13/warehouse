<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jumlah',
        'satuan',
        'kondisi',
        'tanggal_masuk',
        'id_supplier',
    ];

    // Casting tipe data
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah' => 'integer',
        'id_supplier' => 'integer',
    ];

    // Relasi ke Supplier (jika kamu punya model Supplier)

    public function verifikasibarang()
    {
        return $this->hasmany(verifikasibarang::class, 'id_barang');
    }

    public function laporanpenerimaans()
    {
        return $this->belongsTo(laporanpenerimaan::class, 'id_barang');
    }
}