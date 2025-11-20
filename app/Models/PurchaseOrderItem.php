<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'nama_barang',
        'qty',
        'satuan',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relasi ke purchase order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
