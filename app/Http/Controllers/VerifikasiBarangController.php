<?php

// app/Http/Controllers/VerifikasiBarangController.php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\VerifikasiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifikasiBarangController extends Controller
{
    // ✅ WAJIB: Tampilkan daftar barang untuk diverifikasi
    public function index()
    {
        // Tampilkan SEMUA barang + relasi verifikasi (agar bisa edit/tolak)
        $barangs = Barang::with('verifikasiBarang')->latest()->paginate(10);
        return view('verifikasi.index', compact('barangs'));
    }

    // Form verifikasi baru
    public function create(Barang $barang)
    {
        return view('verifikasi.create', compact('barang'));
    }

    // Simpan verifikasi
    public function store(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_valid' => 'required|integer|min:0',
            'kualitas_valid' => 'required|in:baik,sedang,buruk,rusak',
            'status' => 'required|in:verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cegah duplikasi jika perlu
        if ($barang->verifikasiBarang()->exists()) {
            return redirect()->back()->withErrors('Barang ini sudah diverifikasi.');
        }

        $verifikasi = new VerifikasiBarang();
        $verifikasi->id_barang = $barang->id;
        $verifikasi->jumlah_valid = $request->jumlah_valid;
        $verifikasi->kualitas_valid = $request->kualitas_valid;
        $verifikasi->status = $request->status;
        $verifikasi->catatan = $request->catatan;
        $verifikasi->tanggal_verifikasi = now();
        $verifikasi->save();

        return redirect()->route('verifikasi.index')->with('success', 'Verifikasi berhasil disimpan.');
    }

    // Form edit verifikasi
    public function edit(VerifikasiBarang $verifikasi)
    {
        return view('verifikasi.edit', compact('verifikasi'));
    }

    // Update verifikasi
    public function update(Request $request, VerifikasiBarang $verifikasi)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:verified,rejected',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $verifikasi->status = $request->status;
        $verifikasi->catatan = $request->catatan;
        $verifikasi->tanggal_verifikasi = now();
        $verifikasi->save();

        return redirect()->route('verifikasi.index')->with('success', 'Verifikasi berhasil diperbarui.');
    }

}