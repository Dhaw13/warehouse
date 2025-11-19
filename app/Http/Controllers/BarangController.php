<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        // Ambil data barang beserta supplier (biar tidak N+1 query)
        $barangs = Barang::latest()->paginate(10);
        return view('pbarang.index', compact('barangs'));
    }

    public function create()
    {
        return view('pbarang.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:50',
            'kondisi' => 'nullable|in:baru,bekas,rusak,baik,lainnya',
            'tanggal_masuk' => 'required|date',
            'id_supplier' => 'nullable|integer|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Barang::create($request->only([
            'kode_barang',
            'nama_barang',
            'jumlah',
            'satuan',
            'kondisi',
            'tanggal_masuk',
            'id_supplier',
        ]));

        return redirect()->route('pbarang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pbarang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:50',
            'kondisi' => 'nullable|in:baru,bekas,rusak,baik,lainnya',
            'tanggal_masuk' => 'required|date',
            'id_supplier' => 'nullable|integer|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barang->update($request->only([
            'kode_barang',
            'nama_barang',
            'jumlah',
            'satuan',
            'kondisi',
            'tanggal_masuk',
            'id_supplier',
        ]));

        return redirect()->route('pbarang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('pbarang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
