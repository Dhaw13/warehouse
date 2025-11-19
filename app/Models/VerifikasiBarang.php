<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'jumlah_valid',
        'kualitas_valid',
        'status',
        'catatan',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'date',
        'jumlah_valid' => 'integer',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Method: Validasi Barang
    public function validasiBarang(int $jumlahFisik, string $kualitas, ?string $catatan = null): void
    {
        $this->jumlah_valid = $jumlahFisik;
        $this->kualitas_valid = $kualitas;
        $this->catatan = $catatan;
        $this->tanggal_verifikasi = now();
    }

    // Method: Ubah Status
    public function ubahStatus(string $status): void
    {
        $this->status = $status;
    }
}