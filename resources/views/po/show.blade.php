<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Detail Purchase Order</h3>
            <a href="{{ route('po.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">No PO</th>
                                <td>{{ $po->no_po }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal PO</th>
                                <td>{{ $po->tanggal_po->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $po->supplier }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Status</th>
                                <td>
                                    @if($po->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($po->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($po->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-info">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Oleh</th>
                                <td>{{ $po->creator->name }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td><strong>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($po->keterangan)
                    <div class="mt-2">
                        <strong>Keterangan:</strong>
                        <p>{{ $po->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail Barang</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
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
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                                <td><strong>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-3">
            @can('edit po')
                @if($po->status == 'draft')
                    <a href="{{ route('po.edit', $po->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit PO
                    </a>
                @endif
            @endcan

            @can('delete po')
                @if($po->status == 'draft')
                    <form action="{{ route('po.destroy', $po->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus PO ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
</x-app-layout>