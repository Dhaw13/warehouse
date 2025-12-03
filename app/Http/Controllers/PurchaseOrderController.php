<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PurchaseOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view po', only: ['index']),
            new Middleware('permission:create po', only: ['create', 'store']),
            new Middleware('permission:edit po', only: ['edit', 'update']),
            new Middleware('permission:delete po', only: ['destroy']),
        ];
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('creator')->latest()->paginate(10);
        return view('po.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        
        return view('po.create', compact('suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_po' => 'required|date',
            'supplier' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Auto-create supplier jika belum ada
            $supplier = Supplier::firstOrCreate(
                ['nama_supplier' => $request->supplier],
                ['kontak' => null, 'alamat' => null]
            );

            // Buat PO
            $po = PurchaseOrder::create([
                'no_po' => PurchaseOrder::generateNoPo(),
                'tanggal_po' => $request->tanggal_po,
                'supplier' => $supplier->nama_supplier,
                'keterangan' => $request->keterangan,
                'status' => 'draft',
                'created_by' => Auth::id(),
                'total_harga' => 0
            ]);

            // Buat items & auto-create barang jika belum ada
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga_satuan'];
                
                // Auto-create barang jika belum ada (dengan stok 0 dulu)
                $barang = Barang::firstOrCreate(
                    ['nama_barang' => $item['nama_barang']],
                    [
                        'kode_barang' => 'PO-' . strtoupper(substr($item['nama_barang'], 0, 3)) . rand(100, 999),
                        'jumlah' => 0,
                        'satuan' => $item['satuan'],
                        'kondisi' => 'pending', // Nanti diupdate saat verifikasi
                        'tanggal_masuk' => now(),
                        'id_supplier' => $supplier->id
                    ]
                );

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal
                ]);

                $totalHarga += $subtotal;
            }

            $po->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->route('po.index')->with('success', '✅ Purchase Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $po = PurchaseOrder::with('items', 'creator')->findOrFail($id);
        return view('po.show', compact('po'));
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);
        
        if ($po->status !== 'draft') {
            return redirect()->route('po.index')->with('error', 'Hanya PO dengan status draft yang bisa diedit');
        }

        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();

        return view('po.edit', compact('po', 'suppliers', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'draft') {
            return redirect()->route('po.index')->with('error', 'Hanya PO dengan status draft yang bisa diedit');
        }

        $request->validate([
            'tanggal_po' => 'required|date',
            'supplier' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Auto-create supplier jika belum ada
            $supplier = Supplier::firstOrCreate(
                ['nama_supplier' => $request->supplier],
                ['kontak' => null, 'alamat' => null]
            );

            $po->update([
                'tanggal_po' => $request->tanggal_po,
                'supplier' => $supplier->nama_supplier,
                'keterangan' => $request->keterangan,
            ]);

            // Hapus items lama
            $po->items()->delete();

            // Buat items baru
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga_satuan'];
                
                // Auto-create barang jika belum ada
                $barang = Barang::firstOrCreate(
                    ['nama_barang' => $item['nama_barang']],
                    [
                        'kode_barang' => 'PO-' . strtoupper(substr($item['nama_barang'], 0, 3)) . rand(100, 999),
                        'jumlah' => 0,
                        'satuan' => $item['satuan'],
                        'kondisi' => 'pending',
                        'tanggal_masuk' => now(),
                        'id_supplier' => $supplier->id
                    ]
                );

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal
                ]);

                $totalHarga += $subtotal;
            }

            $po->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->route('po.index')->with('success', '✅ Purchase Order berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update PO: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'draft') {
            return redirect()->route('po.index')->with('error', 'Hanya PO dengan status draft yang bisa dihapus');
        }

        $po->delete();
        return redirect()->route('po.index')->with('success', '🗑️ Purchase Order berhasil dihapus!');
    }
}