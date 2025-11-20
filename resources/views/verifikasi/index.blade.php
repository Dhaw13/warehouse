<x-app-layout>
    <div class="container mt-4">
        <h3>Verifikasi Purchase Order</h3>
        <p class="text-muted">Approve atau Reject PO yang sudah disetujui</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No PO</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->no_po }}</td>
                            <td>{{ $po->tanggal_po->format('d M Y') }}</td>
                            <td>{{ $po->supplier }}</td>
                            <td>{{ $po->items->count() }} item</td>
                            <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('verifikasi.show', $po->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada PO yang perlu diverifikasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $purchaseOrders->links() }}
    </div>
</x-app-layout>