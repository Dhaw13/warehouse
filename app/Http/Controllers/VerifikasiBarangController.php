<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\VerifikasiBarang;
use App\Models\LaporanPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiBarangController extends Controller
{
    // Index: Tampilkan PO yang perlu diverifikasi
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('items')
            ->where('status', 'draft')
            ->whereDoesntHave('verifikasi')
            ->latest()
            ->paginate(10);

        return view('verifikasi.index', compact('purchaseOrders'));
    }

    // Show: Detail PO untuk verifikasi
    public function show($poId)
    {
        $po = PurchaseOrder::with('items')->findOrFail($poId);
        $masterBarang = Barang::all();
        
        return view('verifikasi.show', compact('po', 'masterBarang'));
    }

    // Store: Simpan verifikasi
    public function store(Request $request, $poId)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($poId);

        DB::beginTransaction();
        try {
            $status = $request->status;
            $catatan = $request->catatan;

            // Loop semua item PO, buat verifikasi masing-masing
            foreach ($po->items as $item) {
                VerifikasiBarang::create([
                    'purchase_order_id' => $po->id,
                    'po_item_id' => $item->id,
                    'barang_id' => null,
                    'jumlah_diterima' => $status === 'verified' ? $item->qty : 0,
                    'kualitas_valid' => $status === 'verified' ? 'baik' : 'rejected',
                    'status' => $status,
                    'catatan' => $catatan,
                    'tanggal_verifikasi' => now(),
                ]);

                // JIKA APPROVED: Update stok barang
                if ($status === 'verified') {
                    $barang = Barang::firstOrCreate(
                        ['nama_barang' => $item->nama_barang],
                        [
                            'kode_barang' => 'AUTO-' . strtoupper(substr($item->nama_barang, 0, 3)) . rand(100, 999),
                            'jumlah' => 0,
                            'satuan' => $item->satuan,
                            'kondisi' => 'baik',
                            'tanggal_masuk' => now(),
                        ]
                    );
                    $barang->increment('jumlah', $item->qty);

                    // Generate laporan approved
                    LaporanPenerimaan::create([
                        'barang_id' => $barang->id,
                        'periode' => now()->format('F Y'),
                        'tanggal_cetak' => now(),
                        'total_barang' => $item->qty,
                        'file_laporan' => null,
                    ]);
                }

                // JIKA REJECTED: Generate laporan rejected
                if ($status === 'rejected') {
                    // Cari atau buat barang untuk laporan rejected
                    $barang = Barang::where('nama_barang', $item->nama_barang)->first();
                    
                    if (!$barang) {
                        // Buat record barang baru (tanpa stok) untuk laporan
                        $barang = Barang::create([
                            'kode_barang' => 'REJ-' . strtoupper(substr($item->nama_barang, 0, 3)) . rand(100, 999),
                            'nama_barang' => $item->nama_barang,
                            'jumlah' => 0,
                            'satuan' => $item->satuan,
                            'kondisi' => 'rejected',
                            'tanggal_masuk' => now(),
                        ]);
                    }

                    LaporanPenerimaan::create([
                        'barang_id' => $barang->id,
                        'periode' => now()->format('F Y'),
                        'tanggal_cetak' => now(),
                        'total_barang' => 0, // Rejected = 0
                        'file_laporan' => null,
                    ]);
                }
            }

            // Update status PO
            $po->update([
                'status' => $status === 'verified' ? 'approved' : 'rejected'
            ]);

            DB::commit();

            return redirect()->route('laporan.index')
                ->with('success', $status === 'verified' 
                    ? '✅ PO berhasil di-approve! Stok barang telah ditambahkan.' 
                    : '❌ PO ditolak. Laporan penolakan telah dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }
}