<x-app-layout>
    <div class="container mt-4">
        <h3>Laporan Penerimaan Barang</h3>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Periode</th>
                    <th>Tanggal Cetak</th>
                    <th>Total Barang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $index => $laporan)
                    <tr>
                        <td>{{ $laporans->firstItem() + $index }}</td>
                        <td>{{ $laporan->barangs->nama_barang ?? '-' }}</td>
                        <td>{{ $laporan->periode }}</td>
                        <td>{{ $laporan->tanggal_cetak->format('d-m-Y H:i') }}</td>
                        <td>{{ $laporan->total_barang }}</td>
                        <td>
                            @if($laporan->total_barang > 0)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('laporan.download', $laporan->id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada laporan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $laporans->links() }}
    </div>
</x-app-layout>