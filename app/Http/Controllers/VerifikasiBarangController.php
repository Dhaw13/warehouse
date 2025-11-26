<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\VerifikasiBarang;
use App\Models\LaporanPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifikasiBarangController extends Controller
{
    // Index: Tampilkan PO yang perlu diverifikasi (status draft, belum punya verifikasi)
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

    // Store: Simpan verifikasi BARU (single status untuk semua item)
    public function store(Request $request, $poId)
    {
        // Validasi sederhana: status & catatan saja
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($poId);

        DB::beginTransaction();
        try {
            $status = $request->status; // verified atau rejected
            $catatan = $request->catatan;

            // Loop semua item PO, buat verifikasi masing-masing
            foreach ($po->items as $item) {
                VerifikasiBarang::create([
                    'purchase_order_id' => $po->id,
                    'po_item_id' => $item->id,
                    'barang_id' => null, // Bisa diisi manual kalau mau link ke master barang
                    'jumlah_diterima' => $item->qty,
                    'kualitas_valid' => 'baik', // Default
                    'status' => $status,
                    'catatan' => $catatan,
                    'tanggal_verifikasi' => now(),
                ]);

                // ✅ JIKA APPROVED: Update stok barang
                if ($status === 'verified') {
                    // Cari atau buat barang baru di master barang
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

                    // Tambah stok
                    $barang->increment('jumlah', $item->qty);
                }
            }

            // ✅ Update status PO
            $po->update([
                'status' => $status === 'verified' ? 'approved' : 'rejected'
            ]);

            DB::commit();

            // ✅ REDIRECT LOGIC
            if ($status === 'verified') {
                // APPROVED → Balik ke index verifikasi
                return redirect()->route('verifikasi.index')
                    ->with('success', '✅ PO berhasil di-approve! Stok barang telah ditambahkan.');
            } else {
                // REJECTED → Generate laporan & redirect ke halaman laporan
                $this->generateLaporan($po);

                return redirect()->route('laporan.index')
                    ->with('success', '❌ PO ditolak. Laporan penolakan telah dibuat.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    // Helper: Generate laporan otomatis untuk PO yang rejected
    protected function generateLaporan($po)
    {
        foreach ($po->items as $item) {
            // Cari barang (kalau ada)
            $barang = Barang::where('nama_barang', $item->nama_barang)->first();

            if ($barang) {
                LaporanPenerimaan::create([
                    'barang_id' => $barang->id,
                    'periode' => now()->format('F Y'),
                    'tanggal_cetak' => now(),
                    'total_barang' => 0, // Rejected = 0 barang masuk
                    'file_laporan' => null,
                ]);
            }
        }
    }
}