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
        $laporans = LaporanPenerimaan::with('barangs')->latest()->paginate(10);
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

        $laporan = LaporanPenerimaan::create([
            'barang_id' => $barang->id,
            'periode' => $request->periode,
            'tanggal_cetak' => now(),
            'total_barang' => $barang->jumlah,
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