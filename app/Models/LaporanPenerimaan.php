<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenerimaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'tanggal_cetak',
        'total_barang',
        'file_laporan',
    ];

    protected $casts = [
        'tanggal_cetak' => 'date',
        'total_barang' => 'integer',
    ];

    public function barangs()
    {
        return $this->hasone(Barang::class);
    }

    
}