<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenerimaan extends Model
{
    use HasFactory;

   protected $fillable = [
    'barang_id',
    'purchase_order_id',
    'nama_barang',
    'periode',
    'tanggal_cetak',
    'total_barang',
    'status',
    'catatan',
    'file_laporan',
];

    protected $casts = [
        'tanggal_cetak' => 'date',
        'total_barang' => 'integer',
    ];

    // FIX: Changed from hasOne to belongsTo
    public function barangs()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}