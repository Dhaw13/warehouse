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
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('items')
            ->where('status', 'draft')
            ->whereDoesntHave('verifikasi')
            ->latest()
            ->paginate(10);

        return view('verifikasi.index', compact('purchaseOrders'));
    }

    public function show($poId)
    {
        $po = PurchaseOrder::with('items')->findOrFail($poId);
        $masterBarang = Barang::all();
        
        return view('verifikasi.show', compact('po', 'masterBarang'));
    }

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

            foreach ($po->items as $item) {
                // Cari atau buat barang di master
                $barang = Barang::where('nama_barang', $item->nama_barang)->first();
                
                if (!$barang) {
                    // Buat barang baru di master
                    $barang = Barang::create([
                        'kode_barang' => 'BRG-' . strtoupper(substr($item->nama_barang, 0, 3)) . rand(1000, 9999),
                        'nama_barang' => $item->nama_barang,
                        'jumlah' => 0,
                        'satuan' => $item->satuan,
                        'kondisi' => $status === 'verified' ? 'baik' : 'rejected',
                        'tanggal_masuk' => now(),
                        'id_supplier' => null // Bisa ditambahkan relasi ke supplier
                    ]);
                }

                // Buat record verifikasi
                VerifikasiBarang::create([
                    'purchase_order_id' => $po->id,
                    'po_item_id' => $item->id,
                    'barang_id' => $barang->id,
                    'jumlah_diterima' => $status === 'verified' ? $item->qty : 0,
                    'kualitas_valid' => $status === 'verified' ? 'baik' : 'rejected',
                    'status' => $status,
                    'catatan' => $catatan,
                    'tanggal_verifikasi' => now(),
                ]);

                // JIKA APPROVED: Update stok barang
                if ($status === 'verified') {
                    $barang->increment('jumlah', $item->qty);
                    $barang->update([
                        'kondisi' => 'baik',
                        'tanggal_masuk' => now()
                    ]);
                }

                // Generate laporan untuk setiap item
                LaporanPenerimaan::create([
                    'barang_id' => $barang->id,
                    'periode' => now()->format('F Y'),
                    'tanggal_cetak' => now(),
                    'total_barang' => $status === 'verified' ? $item->qty : 0,
                    'file_laporan' => null,
                ]);
            }

            // Update status PO
            $po->update([
                'status' => $status === 'verified' ? 'approved' : 'rejected'
            ]);

            DB::commit();

            $message = $status === 'verified' 
                ? '✅ PO ' . $po->no_po . ' berhasil di-APPROVE! Stok barang telah ditambahkan ke gudang.' 
                : '❌ PO ' . $po->no_po . ' DITOLAK. Laporan penolakan telah dibuat.';

            return redirect()->route('laporan.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }
}