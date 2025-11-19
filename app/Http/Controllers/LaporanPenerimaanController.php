<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanPenerimaan;
use Illuminate\Http\Request;
use PDF;

class LaporanPenerimaanController extends Controller
{
    public function index()
    {
        $laporans = LaporanPenerimaan::with('barangs')->latest()->paginate(10);
        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $barangs = Barang::all(); // biar user bisa pilih barang
        return view('laporan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string',
            'id_barang' => 'required|exists:barangs,id',
        ]);

        // Ambil data barang
        $barang = Barang::findOrFail($request->id_barang);

        // Simpan laporan
        $laporan = LaporanPenerimaan::create([
            'barang_id' => $barang->id, 
            'periode' => $request->periode,
            'tanggal_cetak' => now()->toDateString(),
            'total_barang' => $barang->jumlah,
            'file_laporan' => null,
        ]);

        // Generate PDF laporan
        $pdfPath = $this->generatePDF($barang, $laporan);

        // Update file laporan
        $laporan->update(['file_laporan' => $pdfPath]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat!');
    }

    protected function generatePDF($barang, $laporan)
    {
        $pdf = PDF::loadView('laporan.pdf', [
            'barang' => $barang,
            'laporan' => $laporan,
        ]);

        $filename = "laporan_penerimaan_{$laporan->id}.pdf";
        $path = "laporan/{$filename}";
        \Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function download($id)
    {
        $laporan = LaporanPenerimaan::findOrFail($id);

        if (!$laporan->file_laporan) {
            return redirect()->back()->with('error', 'File laporan belum tersedia.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_laporan);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
