<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'po_item_id',
        'barang_id',
        'jumlah_diterima',
        'kualitas_valid',
        'status',
        'catatan',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'date',
        'jumlah_diterima' => 'integer',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function poItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'po_item_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}