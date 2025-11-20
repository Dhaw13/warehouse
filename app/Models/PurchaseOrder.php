<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_po',
        'tanggal_po',
        'supplier',
        'keterangan',
        'status',
        'total_harga',
        'created_by'
    ];

    protected $casts = [
        'tanggal_po' => 'date',
        'total_harga' => 'decimal:2'
    ];

    // Relasi ke items
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Relasi ke user yang buat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function verifikasi()
{
    return $this->hasMany(VerifikasiBarang::class, 'id_barang');
}

    // Generate nomor PO otomatis
    public static function generateNoPo()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastPo = self::whereYear('tanggal_po', $year)
                      ->whereMonth('tanggal_po', $month)
                      ->orderBy('id', 'desc')
                      ->first();

        if ($lastPo) {
            $lastNumber = (int) substr($lastPo->no_po, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "PO-{$year}{$month}-{$newNumber}";
    }
}