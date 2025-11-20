<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Purchase Order (PO)</h3>
            @can('create po')
                <a href="{{ route('po.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat PO Baru
                </a>
            @endcan
        </div>

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
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->no_po }}</td>
                            <td>{{ $po->tanggal_po->format('d M Y') }}</td>
                            <td>{{ $po->supplier }}</td>
                            <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
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
                            <td>{{ $po->creator->name }}</td>
                            <td class="aksi-col">
                                <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @can('edit po')
                                    @if($po->status == 'draft')
                                        <a href="{{ route('po.edit', $po->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('delete po')
                                    @if($po->status == 'draft')
                                        <form action="{{ route('po.destroy', $po->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Yakin hapus PO ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada Purchase Order</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $purchaseOrders->links() }}
    </div>
</x-app-layout>