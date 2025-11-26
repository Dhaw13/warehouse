<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\VerifikasiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiBarangController extends Controller
{
    // Index: Tampilkan PO yang perlu diverifikasi (status draft, belum punya verifikasi)
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('items')
            ->where('status', 'draft') // PO yang masih draft
            ->whereDoesntHave('verifikasi') // Belum ada verifikasi
            ->latest()
            ->paginate(10);

        return view('verifikasi.index', compact('purchaseOrders'));
    }

    // Show: Detail PO untuk verifikasi
    public function show($poId)
{
    $po = PurchaseOrder::with('items')->findOrFail($poId);
    
    // Ambil semua master barang untuk mapping (kalau nanti mau dropdown di form)
    $masterBarang = Barang::all();
    
    return view('verifikasi.show', compact('po', 'masterBarang'));
}

    // Store: Simpan verifikasi per item PO
    public function store(Request $request, $poId)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.po_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.barang_id' => 'required|exists:barangs,id', // Link ke master barang
            'items.*.jumlah_diterima' => 'required|integer|min:0',
            'items.*.kualitas_valid' => 'required|in:baik,rusak,cacat',
            'items.*.status' => 'required|in:verified,rejected',
            'items.*.catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($poId);

        DB::beginTransaction();
        try {
            $allVerified = true;

            foreach ($request->items as $itemData) {
                // Simpan verifikasi
                VerifikasiBarang::create([
                    'purchase_order_id' => $po->id,
                    'po_item_id' => $itemData['po_item_id'],
                    'barang_id' => $itemData['barang_id'],
                    'jumlah_diterima' => $itemData['jumlah_diterima'],
                    'kualitas_valid' => $itemData['kualitas_valid'],
                    'status' => $itemData['status'],
                    'catatan' => $itemData['catatan'] ?? null,
                    'tanggal_verifikasi' => now(),
                ]);

                // Update stok barang HANYA jika verified
                if ($itemData['status'] === 'verified') {
                    $barang = Barang::find($itemData['barang_id']);
                    $barang->increment('jumlah', $itemData['jumlah_diterima']);
                } else {
                    $allVerified = false;
                }
            }

            // Update status PO
            $po->update([
                'status' => $allVerified ? 'completed' : 'rejected'
            ]);

            DB::commit();
            return redirect()->route('verifikasi.index')
                ->with('success', 'Verifikasi berhasil! Stok barang telah diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }
}