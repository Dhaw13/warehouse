 <x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Laporan Penerimaan Barang</h3>
           
        </div>
        

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>periode</th>
                    <th>Tanggal Cetak</th>
                    <th>Total Barang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $index => $laporan)
                    <tr>
                        <td>{{ $laporans->firstItem() + $index }}</td>
                        <td>{{ $laporan->barangs->nama_barang ?? '-' }}</td>
                        <td>{{ $laporan->periode }}</td>
                        <td>{{ $laporan->tanggal_cetak ? $laporan->tanggal_cetak->format('d-m-Y') : '-' }}</td>
                        <td>{{ $laporan->total_barang }}</td>
                        <td>
                            @if($laporan->file_laporan)
                                <a href="{{ route('laporan.download', $laporan->id) }}" class="btn btn-sm btn-success">
                                    Download PDF
                                </a>
                            @else
                                <span class="text-muted">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                @empty
                  <a href="#" class="btn btn-primary ">Download PDF</a>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada laporan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $laporans->links() }}
    </div>
   <td>
    @if($laporan->file_laporan)
        <a href="{{ route('laporan.download', $laporan->id) }}" 
           class="btn btn-sm btn-success">
            <i class="bi bi-download"></i> Download PDF
        </a>
    @else
        <!-- Untuk rejected PO, generate on-the-fly -->
        <a href="{{ route('laporan.download', $laporan->id) }}" 
           class="btn btn-sm btn-primary">
            <i class="bi bi-file-pdf"></i> Download PDF
        </a>
    @endif
</td>
</x-app-layout>
