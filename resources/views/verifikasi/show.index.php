<x-app-layout>
    <div class="container mt-4">
        <h3>Verifikasi PO: {{ $po->no_po }}</h3>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Supplier:</strong> {{ $po->supplier }}</p>
                        <p><strong>Tanggal:</strong> {{ $po->tanggal_po->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Harga:</strong> Rp {{ number_format($po->total_harga, 0, ',', '.') }}</p>
                        <p><strong>Keterangan:</strong> {{ $po->keterangan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Detail Barang</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Verifikasi -->
        <form action="{{ route('verifikasi.store', $po->id) }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-header bg-warning">
                    <h5>Verifikasi PO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status Verifikasi *</label>
                        <select name="status" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="verified">✅ Approve (Terima Barang)</option>
                            <option value="rejected">❌ Reject (Tolak Barang)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Simpan Verifikasi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>