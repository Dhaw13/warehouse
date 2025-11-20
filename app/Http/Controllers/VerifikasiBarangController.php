<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\VerifikasiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiBarangController extends Controller
{
    // Index: Tampilkan PO yang perlu diverifikasi
    public function index()
    {
        // Ambil PO yang sudah approved tapi belum diverifikasi
        $purchaseOrders = PurchaseOrder::with('items')
            ->where('status', 'approved')
            ->whereDoesntHave('verifikasi') // PO yang belum ada verifikasinya
            ->latest()
            ->paginate(10);

        return view('verifikasi.index', compact('purchaseOrders'));
    }

    // Show: Detail PO untuk verifikasi
    public function show($poId)
    {
        $po = PurchaseOrder::with('items')->findOrFail($poId);
        return view('verifikasi.show', compact('po'));
    }

    // Store: Approve/Reject PO sekaligus
    public function store(Request $request, $poId)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($poId);

        DB::beginTransaction();
        try {
            // 1. Simpan verifikasi untuk setiap item PO
            foreach ($po->items as $item) {
                // Cek apakah barang sudah ada di master, kalau belum buat baru
                $barang = Barang::firstOrCreate(
                    ['nama_barang' => $item->nama_barang],
                    [
                        'kode_barang' => 'BRG-' . strtoupper(uniqid()),
                        'jumlah' => 0,
                        'satuan' => $item->satuan,
                        'kondisi' => 'baru',
                        'tanggal_masuk' => now(),
                    ]
                );

                // Jika approved, tambah stok
                if ($request->status === 'verified') {
                    $barang->increment('jumlah', $item->qty);
                }

                // Simpan verifikasi
                VerifikasiBarang::create([
                    'id_barang' => $barang->id,
                    'jumlah_valid' => $item->qty,
                    'kualitas_valid' => 'baik', // Default baik
                    'status' => $request->status,
                    'catatan' => $request->catatan,
                    'tanggal_verifikasi' => now(),
                ]);
            }

            // 2. Update status PO
            $po->update([
                'status' => $request->status === 'verified' ? 'completed' : 'rejected'
            ]);

            DB::commit();
            return redirect()->route('verifikasi.index')->with('success', 'Verifikasi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }
}