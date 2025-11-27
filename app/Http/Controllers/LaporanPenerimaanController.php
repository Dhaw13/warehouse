<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanPenerimaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenerimaanController extends Controller
{
    public function index()
    {
        // ✅ PERBAIKAN: Ambil SEMUA laporan (approved dan rejected)
        $laporans = LaporanPenerimaan::with('barangs')
            ->latest('tanggal_cetak')
            ->paginate(10);
        
        // 🔍 DEBUG: Uncomment untuk cek data
        //dd($laporans->toArray());
        
        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('laporan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string',
            'id_barang' => 'required|exists:barangs,id',
        ]);

        $barang = Barang::findOrFail($request->id_barang);

        // Untuk APPROVED
LaporanPenerimaan::create([
    'barang_id' => $barang->id,
    'purchase_order_id' => $po->id,
    'nama_barang' => $item->nama_barang,
    'periode' => now()->format('F Y'),
    'tanggal_cetak' => now(),
    'total_barang' => $item->qty,
    'status' => 'approved',
    'catatan' => $catatan,
    'file_laporan' => null,
]);

// Untuk REJECTED (tanpa barang_id)
LaporanPenerimaan::create([
    'barang_id' => null, // ← NULL untuk rejected
    'purchase_order_id' => $po->id,
    'nama_barang' => $item->nama_barang,
    'periode' => now()->format('F Y'),
    'tanggal_cetak' => now(),
    'total_barang' => 0,
    'status' => 'rejected',
    'catatan' => $catatan,
    'file_laporan' => null,
]);
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat!');
    }

    // Download PDF
    public function download($id)
    {
        $laporan = LaporanPenerimaan::with('barangs')->findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('laporan.pdf', [
            'laporan' => $laporan,
            'barang' => $laporan->barangs,
        ]);

        $filename = "Laporan_Penerimaan_{$laporan->id}_" . now()->format('Ymd_His') . ".pdf";

        return $pdf->download($filename);
    }
}